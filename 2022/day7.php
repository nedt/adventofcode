<?php

$input = '
';

class ElfDirectory implements RecursiveIterator, ArrayAccess {
	protected $children = [];
	
	public function addFile($name, $size) {
		$this->children[$name] = $size;
	}
	
	public function addDirectory($name, ElfDirectory $directory = null) {
		if (!$directory) {
			$directory = new ElfDirectory();
		}
		$this->children[$name] = $directory;
	}
	
	public function offsetExists($offset): bool {
		return array_key_exists($offset, $this->children);
	}
	
	public function offsetGet($offset): mixed {
		return $this->children[$offset];
	}
	
	public function offsetSet($offset, $value): void {
		$this->children[$offset] = $value;		
	}
	
	public function offsetUnset($offset): void {
		unset($this->children[$offset]);
	}
	
	public function getChildren(): ElfDirectory {
		return $this->current();
	}
	
	public function hasChildren(): bool {
		return $this->current() instanceof ElfDirectory;
	}
	
	public function current(): mixed {
		return current($this->children);		
	}
	
	public function key(): mixed {
		return key($this->children);
	}
	
	public function next(): void {
		next($this->children);		
	}
	
	public function rewind(): void {
		reset($this->children);
	}
	
	public function valid(): bool {
		return key($this->children) !== null;		
	}
	
	public function getTotalSize() {
		$size = 0;
		foreach ($this->children as $child) {
			$size += $child instanceof ElfDirectory ? $child->getTotalSize() : $child;
		}
		return $size;
	}
}

$root = new ElfDirectory();
$current = $root;
$stack = [];

$input = trim($input);
$input = explode("\n", $input);
foreach ($input as $line) {
	$line = explode(' ', $line);
	if ($line[0] != '$') {
		if ($line[0] == 'dir') {
			$current[$line[1]] = new ElfDirectory();
		} else {
			$current[$line[1]] = $line[0];			
		}
	} else if ($line[1] == 'cd') {
		if ($line[2] == '/') {
			$current = $root;
			$stack = [];
		} else if ($line[2] == '..') {
			$current = array_pop($stack);
		} else {
			array_push($stack, $current);
			$current = $current[$line[2]];
		}
	}
}

$sum = 0;
foreach (new RecursiveIteratorIterator($root, RecursiveIteratorIterator::SELF_FIRST) as $item) {
	if (!($item instanceof ElfDirectory)) {
		continue;
	}
	$size = $item->getTotalSize();
	echo $size, "\n";			
	if ($size <= 100000) {
		$sum += $size;
	}
}

echo $sum, "\n\n";

$smallest = $root->getTotalSize();
$needed = 30000000;
$needed -= 70000000 - $root->getTotalSize();
foreach (new RecursiveIteratorIterator($root, RecursiveIteratorIterator::SELF_FIRST) as $item) {
	if (!($item instanceof ElfDirectory)) {
		continue;
	}
	$size = $item->getTotalSize();
	if ($size <= $needed) {
		continue;
	}
	if ($size < $smallest) {
		$smallest = $size;
	}
}
echo $smallest;
