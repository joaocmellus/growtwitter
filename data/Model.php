<?php

require_once __DIR__ . '/DataManager.php';
require_once __DIR__ . '/Database.php';

abstract class Model
{
    protected int $id;
    protected static Database $__db;

    // ---------------------- Métodos para a Classe -------------------
    public static function init()
    {
        static::$__db = DataManager::getDatabase(static::class);
    }

    public static function getDB(): Database
    {
        return static::$__db;
    }

    // ------------ Métodos para adicionar dados à classe -------------
    public function __construct(array $args=[]){
        if (!empty($args)) {
            $this->defaultCreation($args);
        }
    }

    /**
     * Método para criação do modelo, pode ser alterada nas classes filhas para adicionar
     * regras para a criação do modelo. 
     */
    private function defaultCreation(array $args): void
    {
        array_map(
            function ($value, $key) {
                if ($key == 'id' || isset($this->$key)) return;
                $this->set($key, $value);
            },
            $args,
            array_keys($args)
        );
    }

    public static function create(array $args): static
    {
        $model = new static($args);
        $model->save();

        return $model;
    }

    // --------------- Métodos para ler dados da classe ---------------
    public static function all(): array
    {
        return static::$__db->getAll();
    }

    public static function findById(int $id): ?static
    {
        return static::$__db->findById($id);
    }

    public static function find(array $args): array
    {
        return static::$__db->find($args) ?? [];
    }

    // ------------ Métodos para atualizar dados da classe ------------
    public static function update(int $id, array $args): ?static
    {
        return static::$__db->update($id, $args);
    }

    // ------------- Métodos para deletar dados da classe -------------
    public static function delete(int $id): ?static
    {
        return static::$__db->delete($id);
    }

    // -------------------- Métodos para a Instância ------------------
    protected function canSave(): bool
    {
        return true;
    }

    public function save(): static
    {
        if (!$this->canSave()) {
            throw new Exception("Não foi possível salvar a instância de " . static::class, 1);
        }

        if (!isset($this->id)) {
            $id = static::$__db->add($this);
            $this->id = $id;
        } else {
            static::$__db->update($this->getId(), $this->toArray());
        }

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function set(string $property, mixed $value): void
    {
        if (property_exists($this, $property) && $property != 'id') {
            $this->$property = $value;
        } else {
            throw new Exception("A propriedade {$property} não existe");
        }
    }
    
    public function match($args): bool
    {
        foreach ($args as $key => $value) {
            if ($this->$key != $value) {
                return false;
            }
        }
        return true;
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function updateByArray(array $updates): void
    {
        foreach ($updates as $property => $update) {
            $this->set($property, $update);
        }
    }
}
