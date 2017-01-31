<?php
declare(strict_types = 1);

namespace wlatanowicz\DevBundle\Generator;

class TestGenerator
{

    public function generate(string $class)
    {
        $services = $this->getServiceList($class);

        $unit = array_merge(
            $this->generateHeader($class),
            $this->generateUses($services),
            $this->generateClassHeader($class),
            $this->generatePropertyList($class, $services),
            $this->generateBeforeMethod($class, $services),
            $this->generateDummyTestMethod(),
            [
                "}"
            ]
        );

        echo implode("\n", $unit);
    }

    private function getServiceList(string $class): array
    {
        $reflection = new \ReflectionMethod($class, "__construct");
        $parameters = $reflection->getParameters();

        $services = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getClass()->getName();
            $services[] = [
                "type-full" => $type,
                "type" => basename(strtr($type, ["\\" => "/"])),
                "param" => $parameter->getName()
            ];
        }

        return $services;
    }

    private function generateUses(array $services): array
    {
        $lines = [];
        foreach ($services as $service) {
            $lines[] = "use {$service['type-full']} as {$service['type']};";
        }
        return $lines;
    }

    private function generateHeader(string $class): array
    {
        $reflection = new \ReflectionClass($class);
        $namespace = $reflection->getNamespaceName();

        $unitNamespace = "Unit\\" . $namespace;

        return [
            "<?php",
            "declare(strict_types = 1);",
            "",
            "namespace {$unitNamespace};",
            "",
        ];
    }

    private function generateClassHeader(string $class): array
    {
        $className = basename(strtr($class, ["\\" => "/"]));
        return [
            "",
            "class {$className}Test extends \\PHPUnit_Framework_TestCase",
            "{"
        ];
    }

    private function generatePropertyList(string $class, array $services): array
    {
        $properties = [];
        foreach ($services as $service) {
            $varName = $this->varNameFromClass($service['type']) . "Mock";
            $className = $service['type'];
            $properties[] = "    /**";
            $properties[] = "     * @var {$className}|\\PHPUnit_Framework_MockObject_MockObject";
            $properties[] = "     */";
            $properties[] = "    private \${$varName};";
            $properties[] = "";
        }

        $className = basename(strtr($class, ["\\" => "/"]));
        $varName = $this->varNameFromClass($className);

        $properties[] = "    /**";
        $properties[] = "     * @var {$className}";
        $properties[] = "     */";
        $properties[] = "    private \${$varName};";

        return $properties;
    }

    private function generateBeforeMethod(string $class, array $services): array
    {
        $mocks = [];

        foreach ($services as $service) {
            $varName = $this->varNameFromClass($service['type']) . "Mock";
            $className = $service['type'];
            $mocks[] = "        \$this->{$varName} = \$this->createMock({$className}::class);";
        }

        $className = basename(strtr($class, ["\\" => "/"]));
        $varName = $this->varNameFromClass($className);

        $mocks[] = "";
        $mocks[] = "        \$this->{$varName} = \$this->createMock({$className}::class);";

        return array_merge([
            "",
            "    /**",
            "     * @before",
            "     */",
            "    public function prepare()",
            "    {",
            ],
            $mocks,
            [
            "    }",
            ]
        );
    }

    private function varNameFromClass(string $class)
    {
        return strtolower(substr($class, 0, 1)) . substr($class, 1);
    }

    private function generateDummyTestMethod()
    {
        return [
            "",
            "    /**",
            "     * @test",
            "     */",
            "    public function itShouldDoSomething()",
            "    {",
            "        //@TODO implement test",
            "    }",
        ];
    }
}
