<?php
abstract class bb_base
{
	abstract public function create($params); // POST
	abstract public function update($params); // PUT
	abstract public function getData($id); // GET
	abstract public function delete($id); // DELETE
}
?>