
  function processCheckboxes(id) {
    $.ajax( {
        type: 'POST',
        url: './frontend/contentCreatorQuery.php',
        data: {constraintID: id}
    } );

    document.location.reload(true);

  }
