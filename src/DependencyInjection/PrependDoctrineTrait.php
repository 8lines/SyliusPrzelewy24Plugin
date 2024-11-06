<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

trait PrependDoctrineTrait
{
    protected function prependDoctrine(ContainerBuilder $container): void
    {
        if (false === $container->hasExtension('doctrine')) {
            return;
        }

        $pluginDoctrineConfigDir = $this->getPluginDoctrineConfigDir();
        $pluginRootNamespace = $this->getPluginRootNamespace();

        foreach ($this->getEntitiesNamespaces() as $entityNamespace) {
            $entityDirName = \substr($entityNamespace, \strlen($pluginRootNamespace));
            $entityDirName = \str_replace('\\', '/', $entityDirName);
            $entityDirName = \strtolower($entityDirName);

            $entityMappingDir = $pluginDoctrineConfigDir . $entityDirName;

            $container->prependExtensionConfig(
                name: 'doctrine',
                config: [
                    'orm' => [
                        'mappings' => [
                            $entityMappingDir => [
                                'is_bundle' => false,
                                'type' => 'xml',
                                'dir' => $entityMappingDir,
                                'prefix' => $entityNamespace . '\\Entity',
                            ],
                        ],
                    ],
                ],
            );
        }
    }

    private function getPluginDoctrineConfigDir(): string
    {
        $pluginReflection = new \ReflectionClass($this->getPluginRootClass());
        $pluginRootDir = \dirname($pluginReflection->getFileName());

        return $pluginRootDir . '/Resources/config/doctrine';
    }

    private function getPluginRootNamespace(): string
    {
        $pluginReflection = new \ReflectionClass($this->getPluginRootClass());

        return $pluginReflection->getNamespaceName();
    }

    /**
     * @return string<class-string>
     */
    abstract protected function getPluginRootClass(): string;

    /**
     * @return string[]
     */
    abstract protected function getEntitiesNamespaces(): array;
}
