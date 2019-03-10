function processCompletedRows(passedPeopleSoftID) {
  $.ajax( {
      type: 'POST',
      url: './frontend/contentCreatorQuery.php',
      data:	{peopleSoftID: passedPeopleSoftID}
  } );

  document.location.reload(true);

}

function processNewRows(passedPeopleSoftID) {
  $.ajax( {
      type: 'POST',
      url: './frontend/contentCreatorQuery.php',
      data:	{newPeopleSoftID: passedPeopleSoftID}
  } );

  document.location.reload(true);

}
