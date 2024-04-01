<?php

require_once __DIR__ . '/Model.php';

class Database
{
    private int $idCounter = 1;
    private array $data = [];
    private Model $modelClass;

    public function __construct(string $modelClass)
    {
        // Verifica se a classe existe
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException("A classe \"$modelClass\" não existe.");
        }

        // Verifica se a classe é uma subclasse de Model
        $reflectedClass = new ReflectionClass($modelClass);

        if (!$reflectedClass->isSubclassOf(Model::class)) {
            throw new InvalidArgumentException("A classe \"$modelClass\" não estende a classe Model.");
        }
        $this->modelClass = new $modelClass;
    }

    // ----------------------------------------------------------------
    private function setId(): int
    {
        return $this->idCounter++;
    }

    public function getTotalCount(): int
    {
        return count($this->data);
    }

    // ------------ Métodos para adicionar dados à classe -------------
    public function add(Model $data): int
    {
        if ($data instanceof $this->modelClass) {
            $data->setId($this->setId());
            array_push($this->data, $data);

            return $data->getId();
        }
        return -1;
    }

    // --------------- Métodos para ler dados da classe ---------------
    public function getAll()
    {
        return $this->data;
    }

    public function find(array $args): ?array
    {
        // Verificar o array data para ver se as keys batem
        $matches = array_values(array_filter(
            $this->data,
            function ($item) use ($args) {
                return $item->match($args);
            }
        ));
        return empty($matches) ? null : $matches;
    }

    public function findById(int $id): ?Model
    {
        foreach ($this->data as $item) {
            if ($id === $item->getId()) {
                return $item;
            }
        }
    }

    // ------------ Métodos para atualizar dados da classe ------------
    public function update(int $id, array $data): ?Model
    {
        $item = $this->findById($id);
        if ($item) {
            $item->updateByArray($data);
            return $item;
        }
    }
    // ------------- Métodos para deletar dados da classe -------------
    public function delete(int $id): ?Model
    {
        foreach ($this->data as $index => $item) 
        {
            if ($id === $item->getId()) {
                unset($this->data[$index]);
                return $item;
            }
        }
        return null;
    }
}
