<?php

class Rights
{
    var $read = true;
    var $update = true;
    var $drop = true;
    var $grant=true;
    var $canaddcat = false;
    var $canadddoc = false;
    var $canaddcomment = false;
    
    public function __construct()
    {
    }

    public function canRead()
    {
        return $this->read;
    }
    public function canUpdate()
    {
        return $this->update;
    }
    public function canDrop()
    {
        return $this->drop;
    }
    public function canGrant()
    {
        return $this->grant;
    }
    public function canAddCat()
    {
        return $this->canaddcat;
    }
    public function canAddDoc()
    {
        return $this->canadddoc;
    }
    public function canAddComment()
    {
        return $this->canaddcomment;
    }
}
