<?php

namespace UOradea\Exec;

use Exception;

class Node {
    
    public $parentNode = null;
    public $firstChild = null;
    public $lastChild = null;
    public $nextSibling = null;
    public $previousSibling = null;
    
    public function appendChild(self $node) : self {
        
        $node->parentNode = $this;
        
        if ($this->lastChild === null) {
            $this->lastChild = $this->firstChild = $node;
        } else {        
            $this->lastChild->nextSibling = $node;
            $node->previousSibling = $this->lastChild;
            $node->nextSibling = null;
            $this->lastChild = $node;    
        }
        
        return $node;
        
    }
    
    public function prepandChild(self $node) : self {
        
        $node->parentNode = $this;
        
        if ($this->firstChild === null) {
            $this->firstChild = $this->lastChild = $node;
        } else {        
            $this->firstChild->previuosSibling = $node;
            $node->nextSibling = $this->firstChild;
            $this->firstChild = $node;
        }
        
        return $node;
    }
    
    public function removeChild(self $node) : self {
        if ($node->parentNode !== $this) {
            throw new Exception("Nu poci!");
        }
        
        $prev = $node->previousSibling;
        $next = $node->nextSibling;
        
        if ($prev !== null) {
            $prev->nextSibling = $next;
            if ($next !== null) {
                $next->previousSibling = $prev;
            } else {
                $this->lastChild = $prev;
            }
        } elseif ($next !== null) {
            $next->previousSibling = null;
            $this->firstChild = $next;
        } else {
            $this->firstChild =
            $this->lastChild = null;
        }
        
        $node->parentNode = $node->previousSibling = $node->nextSibling = null;
        return $node;
    }
    
    public function insertBefore(self $node, self $ref) : self {
        if ($ref->parentNode !== $this) {
            throw new Exception("Nu poci!");
        }
        
        $node->parentNode = $this;
        
        if ($ref === $this->firstChild) {
            $this->prepandChild($node);
        } else {            
            $ref->previuosSibling->nextSibling = $node;
            $node->previuosSibling = $ref->previuosSibling;
            $node->nextSibling = $ref;
            $ref->previousSibling = $node;
        }
        
        return $node;
    }
    
    public function insertAfter(self $node, self $ref) : self {
        if ($ref->parentNode !== $this) {
            throw new Exception("Nu poci!");
        }
        
        if ($ref === $this->lastChild) {
            $this->appendChild($node);
        } else {
            $ref->nextSibling->previousSibling = $node;
            $node->nextSibling = $ref->nextSibling;
            $node->previousSibling = $ref;
            $ref->nextSibling = $node;
        }
        
        $node->parentNode = $this;
        
        return $node;
    }
    
    public function walkChildren() {
        for ($node = $this->firstChild; $node !== null; $node = $node->nextSibling) {
            yield $node;
        }
    }
}