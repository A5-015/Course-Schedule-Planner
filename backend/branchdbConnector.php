public function querySet($passedQuery, $passedArray)
{
  $returnArray = [];
  $x =0;
  while ($x < sizeof(passedArray)){
    $list = $this->connection->query($passedQuery);
    $result = arrayify($list);
    array_push($returnArray, $result);
    $x++;
  }

  return $returnArray;
}
