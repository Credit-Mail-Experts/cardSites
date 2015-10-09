function notMe() {
    var customerNumber = document.forms['CustomerNumberConfirmationForm'].elements['CustomerNumberTextBox'].value;

    $(function() {
        $( "#customer-number-correct" ).dialog({
            resizable: false,
            height:300,
            width:500,
            modal: true,
            buttons: {
                Yes: function() {
                    $( this ).dialog( "close" );
                    $(function() {
                        $( "#family" ).dialog({
                            resizable: false,
                            height:250,
                            width:500,
                            modal: false,
                            buttons: {
                                Yes: function() {
                                    location.href = 'form.php?family=yes&customerNumber=' + customerNumber;
                                },
                                No: function() {
                                    $( this ).dialog( "close" );
                                    $(function() {
                                        $( "#friend" ).dialog({
                                            resizable: false,
                                            height:250,
                                            width:500,
                                            modal: false,
                                            buttons: {
                                                Yes: function() {
                                                    location.href = 'form.php?friend=yes&customerNumber=' + customerNumber;
                                                },
                                                No: function() {
                                                    location.href = 'form.php';
                                                }
                                            }
                                        });
                                    });
                                }
                            }
                        });
                    });
                },
                No: function() {
                    location.href = 'index.php';
                }
            }
        });
    });
}

function customerNumberEmpty() {
    if (document.forms['CustomerNumberForm'].elements['CustomerNumberTextBox'].value) {
        return true;
    } else {
        $(function() {
            $( "#enter-customer-number" ).dialog({
                resizable: false,
                height: 250,
                width: 700,
                modal: true,
                buttons: {
                    Ok: function() {
                        $( this ).dialog( "close" );
                    },
                    "No Customer Number": function() {
                        location.href = 'form.php';
                    }
                }
            });
        });
        return false;
    }
}

function noMatch() {
    $(function() {
        $( "#no-match-found" ).dialog({
            resizable: false,
            height: 300,
            width: 800,
            modal: true,
            buttons: {
                "Re-enter My Customer Number": function() {
                    $( this ).dialog( "close" );
                },
                "Continue Without a Customer Number": function() {
                    location.href = 'form.php';
                }
            }
        });
    });
}