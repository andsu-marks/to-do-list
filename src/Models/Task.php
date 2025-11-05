<?php
namespace App\Models;

class Task {
    private string $file = __DIR__ . '/../../tasks.json';
    private array $tasks = [];

    public function getAll() {
        $this->load();
        return $this->tasks;
    }

    public function save($data) {
        $this->load();
        $this->tasks[] = $data;
        $this->persist();
    }

    private function load(): void {
        if (!file_exists($this->file)) return;
        $this->tasks = json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function persist(): void {
        file_put_contents($this->file, json_encode($this->tasks, JSON_PRETTY_PRINT));
    }

    public function getByUid($uid) {
        $this->load();
        foreach($this->tasks as $task) {
            if ($task['uid'] === $uid) {
                return $task;
            }
        }
        return null;
    }

    public function update($uid, $data) {
        $this->load();
        $task = $this->getByUid($uid);
        if (!$task) return false;

        foreach($data as $key =>$value) {
            if ($key !== 'uid') {
                $task[$key] = $value;
            }
        }

        foreach ($this->tasks as $index => $t) {
            if ($t['uid'] === $uid) {
                $this->tasks[$index] = $task;
                break;
            }
        }

        $this->persist();
        return true;
    }

    public function delete(string $uid): bool {
        $this->load();

        $task = $this->getByUid($uid);
        if (!$task) return false;

        $this->tasks = array_values(
            array_filter($this->tasks, fn($t) => $t['uid'] !== $uid)
        );

        $this->persist();
        return true;
    }
}