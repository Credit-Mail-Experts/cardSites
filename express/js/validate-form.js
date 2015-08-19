$(document).ready(function(){
    // input variables
    var form = $("#CustomerInformationForm");
    var firstName = $("#FirstNameTextBox");
    var middleName = $("#MiddleNameTextBox");
    var lastName = $("#LastNameTextBox");
    var homePhoneOne = $("#HomePhoneTextBoxOne");
    var homePhoneTwo = $("#HomePhoneTextBoxTwo");
    var homePhoneThree = $("#HomePhoneTextBoxThree");
    var workPhoneOne = $("#WorkPhoneTextBoxOne");
    var workPhoneTwo = $("#WorkPhoneTextBoxTwo");
    var workPhoneThree = $("#WorkPhoneTextBoxThree");
    var cellPhoneOne = $("#CellPhoneTextBoxOne");
    var cellPhoneTwo = $("#CellPhoneTextBoxTwo");
    var cellPhoneThree = $("#CellPhoneTextBoxThree");
    var email = $("#EmailTextBox");
    var addressOne = $("#AddressOneTextBox");
    var addressTwo = $("#AddressTwoTextBox");
    var city = $("#CityTextBox");
    var state = $("#StateDropDownList");
    var zip = $("#ZipTextBox");
        
    // error span variables
    var firstNameError = $("#FirstNameError");
    var middleNameError = $("#MiddleNameError");
    var lastNameError = $("#LastNameError");
    var homePhoneError = $("#HomePhoneError");
    var workPhoneError = $("#WorkPhoneError");
    var cellPhoneError = $("#CellPhoneError");
    var phonesError = $("#PhonesError");
    var emailError = $("#EmailError");
    var addressOneError = $("#AddressOneError");
    var addressTwoError = $("#AddressTwoError");
    var cityError = $("#CityError");
    var stateError = $("#StateError");
    var zipError = $("#ZipError");
	
    //On blur
    firstName.blur(validateFirstName);
    middleName.blur(validateMiddleName);
    lastName.blur(validateLastName);
    homePhoneThree.blur(validateHomePhone);
    workPhoneThree.blur(validateWorkPhone);
    cellPhoneThree.blur(validateCellPhone);
    homePhoneThree.blur(validatePhones);       
    workPhoneThree.blur(validatePhones);
    cellPhoneThree.blur(validatePhones);
    email.blur(validateEmail);       
    addressOne.blur(validateAddressOne);
    addressTwo.blur(validateAddressTwo);       
    city.blur(validateCity);
    state.blur(validateState);
    zip.blur(validateZip);

    //On Submitting
    form.submit(function(){
        if(validateFirstName() & validateMiddleName() & validateLastName() & validateHomePhone() & validateWorkPhone() & validateCellPhone() & validateEmail() & validateAddressOne() & validateAddressTwo() & validateCity() & validateState() & validateZip() & validatePhones())
            return true
        else
            return false;
    });
	
    //validation functions   
    function validateFirstName(){
        var a = $("#FirstNameTextBox").val();
        var filter = /^[a-zA-Z-]*$/;
                
        if (a.length < 1) {
            firstNameError.text("Required Field!");
            firstNameError.addClass("invalid");
            firstName.addClass("invalid");
            return false;
        } else {
            if (filter.test(a)){
                firstNameError.removeClass("invalid");
                firstName.removeClass("invalid");
                firstName.addClass("valid");
                return true;
            } else {
                firstNameError.text("Name must only contain letters!");
                firstNameError.addClass("invalid");
                firstName.addClass("invalid");
                return false;
            }
        }
    }
    
    function validateMiddleName(){
        var a = $("#MiddleNameTextBox").val();
        var filter = /^[a-zA-Z-]*$/;
                
        if (filter.test(a)){
            middleNameError.removeClass("invalid");
            middleName.removeClass("invalid");
            middleName.addClass("valid");
            return true;
        } else {
            middleNameError.text("Name must only contain letters!");
            middleNameError.addClass("invalid");
            middleName.addClass("invalid");
            return false;
        }
    }
    
    function validateLastName(){
        var a = $("#LastNameTextBox").val();
        var filter = /^[a-zA-Z-]*$/;
                
        if (a.length < 1) {
            lastNameError.text("Required Field!");
            lastNameError.addClass("invalid");
            lastName.addClass("invalid");
            return false;
        } else {
            if (filter.test(a)){
                lastNameError.removeClass("invalid");
                lastName.removeClass("invalid");
                lastName.addClass("valid");
                return true;
            } else {
                lastNameError.text("Name must only contain letters!");
                lastNameError.addClass("invalid");
                lastName.addClass("invalid");
                return false;
            }
        }
    }
    
    function validatePhones(){
        var a = $("#HomePhoneTextBoxOne").val();
        var b = $("#WorkPhoneTextBoxOne").val();
        var c = $("#CellPhoneTextBoxOne").val();
                
        if (a.length > 0 || b.length > 0 || c.length > 0) {
            phonesError.removeClass("invalid");
            return true;
        } else {
            phonesError.text("Please fill out at least 1 of the 3 phone fields!");
            phonesError.addClass("invalid");
            return false;
        }
    }
    
    function validateHomePhone(){
        var a = $("#HomePhoneTextBoxOne").val();
        var b = $("#HomePhoneTextBoxTwo").val();
        var c = $("#HomePhoneTextBoxThree").val();
        var filter = /\d{3}/;
                
        if (a.length < 1 & b.length < 1 & c.length < 1) {
            homePhoneError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a) & filter.test(b) & filter.test(c)){
                homePhoneError.removeClass("invalid");
                return true;
            } else {
                homePhoneError.text("Fill out all 3 portions of the phone fields with numbers only!");
                homePhoneError.removeClass("valid");
                homePhoneError.addClass("invalid");
                return false;
            }
        } 
    }
    
    function validateWorkPhone(){
        var a = $("#WorkPhoneTextBoxOne").val();
        var b = $("#WorkPhoneTextBoxTwo").val();
        var c = $("#WorkPhoneTextBoxThree").val();
        var filter = /\d{3}/;
                
        if (a.length < 1 & b.length < 1 & c.length < 1) {
            workPhoneError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a) & filter.test(b) & filter.test(c)){
                workPhoneError.removeClass("invalid");
                return true;
            } else {
                workPhoneError.text("Fill out all 3 portions of the phone fields with numbers only!");
                workPhoneError.removeClass("valid");
                workPhoneError.addClass("invalid");
                return false;
            }
        } 
    }
    
    function validateCellPhone(){
        var a = $("#CellPhoneTextBoxOne").val();
        var b = $("#CellPhoneTextBoxTwo").val();
        var c = $("#CellPhoneTextBoxThree").val();
        var filter = /\d{3}/;
                
        if (a.length < 1 & b.length < 1 & c.length < 1) {
            cellPhoneError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a) & filter.test(b) & filter.test(c)){
                cellPhoneError.removeClass("invalid");
                return true;
            } else {
                cellPhoneError.text("Fill out all 3 portions of the phone fields with numbers only!");
                cellPhoneError.removeClass("valid");
                cellPhoneError.addClass("invalid");
                return false;
            }
        } 
    }
    
    function validateEmail(){
        var a = $("#EmailTextBox").val();
        var filter = /^([0-9a-zA-Z]([-\.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/;

        if (a.length < 1) {
            emailError.text("Required Field!");
            emailError.addClass("invalid");
            email.addClass("invalid");
            return false;
        } else {
            if(filter.test(a)){
                emailError.removeClass("invalid");
                email.removeClass("invalid");
                email.addClass("valid");
                return true;
            } else {
                emailError.text("Invalid Email!");
                emailError.addClass("invalid");
                email.addClass("invalid");
                return false;
            }
        }
    }
    
    function validateAddressOne(){
        var a = $("#AddressOneTextBox").val();
                
        if (a.length < 1) {
            addressOneError.text("Required Field!");
            addressOneError.addClass("invalid");
            addressOne.addClass("invalid");
            return false;
        } else {
            addressOne.removeClass("invalid");
            addressOne.addClass("valid");
            addressOneError.removeClass("invalid");
            return true;
        }
    }
    
    function validateAddressTwo(){
        var a = $("#AddressTwoTextBox").val();
                
        if (a.length < 1) {
            addressTwo.addClass("valid");
            return true;
        } else {
            if (a.length < 1) {
                addressTwoError.text("Required Field!");
                addressTwoError.addClass("invalid");
                addressTwo.addClass("invalid");
                return false;
            } else {
                addressTwo.addClass("valid");
                return true;
            }
        }
    }
    
    function validateCity(){
        var a = $("#CityTextBox").val();
        var filter = /^[a-zA-Z- ]*$/;
                
        if (a.length < 1) {
            cityError.text("Required Field!");
            cityError.addClass("invalid");
            city.addClass("invalid");
            return false;
        } else {
            if (filter.test(a)){
                cityError.removeClass("invalid");
                city.removeClass("invalid");
                city.addClass("valid");
                return true;
            } else {
                cityError.text("City must only contain letters!");
                cityError.addClass("invalid");
                city.addClass("invalid");
                return false;
            }
        }
    }
    
    function validateState(){
        var a = $("#StateDropDownList").val();
                
        if (a.length < 1) {
            stateError.text("Required Field!");
            stateError.addClass("invalid");
            return false;
        } else {
            stateError.removeClass("invalid");
            return true;
        }
    }
    
    function validateZip(){
        var a = $("#ZipTextBox").val();
        var filter = /\d{5}/;
                
        if (a.length < 1) {
            zipError.text("Required Field!");
            zipError.addClass("invalid");
            zip.addClass("invalid");
            return false;
        } else {
            if (filter.test(a)){
                zipError.removeClass("invalid");
                zip.removeClass("invalid");
                zip.addClass("valid");
                return true;
            } else {
                zipError.text("Enter a five digit zip!");
                zipError.addClass("invalid");
                zip.addClass("invalid");
                return false;
            }
        }
    }
});

