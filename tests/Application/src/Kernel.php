<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\App;

use Sylius\Bundle\CoreBundle\SyliusCoreBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log';
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    public function registerBundles(): iterable
    {
        foreach ($this->getConfigurationDirectories() as $confDir) {
            $bundlesFile = $confDir . '/bundles.php';
            if (false === is_file($bundlesFile)) {
                continue;
            }
            yield from $this->registerBundlesFromFile($bundlesFile);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        foreach ($this->getConfigurationDirectories() as $confDir) {
            $this->loadRoutesConfiguration($routes, $confDir);
        }
    }

    private function isTestEnvironment(): bool
    {
        return 0 === strpos($this->getEnvironment(), 'test');
    }

    private function loadRoutesConfiguration(RoutingConfigurator $routes, string $confDir): void
    {
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS);
        $routes->import($confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS);
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS);
    }

    /**
     * @return BundleInterface[]
     */
    private function registerBundlesFromFile(string $bundlesFile): iterable
    {
        $contents = require $bundlesFile;
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    /**
     * @return string[]
     */
    private function getConfigurationDirectories(): iterable
    {
        yield $this->getProjectDir() . '/config';
        $syliusConfigDir = $this->getProjectDir() . '/config/sylius/' . SyliusCoreBundle::MAJOR_VERSION . '.' . SyliusCoreBundle::MINOR_VERSION;
        if (is_dir($syliusConfigDir)) {
            yield $syliusConfigDir;
        }
        $symfonyConfigDir = $this->getProjectDir() . '/config/symfony/' . BaseKernel::MAJOR_VERSION . '.' . BaseKernel::MINOR_VERSION;
        if (is_dir($symfonyConfigDir)) {
            yield $symfonyConfigDir;
        }
    }
}
