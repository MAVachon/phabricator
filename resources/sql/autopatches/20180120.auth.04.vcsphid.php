<?php

// Populate VCS passwords (which we copied from the old "VCS Password" table
// in the last migration) with new PHIDs.

$table = new PhabricatorAuthPassword();
$conn = $table->establishConnection('w');

foreach (new LiskMigrationIterator($table) as $row) {
  if ($row->getPHID()) {
    continue;
  }

  $new_phid = $row->generatePHID();

  queryfx(
    $conn,
    'UPDATE %T SET phid = %s WHERE id = %d',
    $table->getTableName(),
    $new_phid,
    $row->getID());
}