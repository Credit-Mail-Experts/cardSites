$(document).ready(function(){
    // input variables
    var form = $("#CustomerInformationForm");
    var firstName = $("#FirstNameTextBox");
    var middleName = $("#MiddleNameTextBox");
    var lastName = $("#LastNameTextBox");
    var callerId = $("#CallerIdTextBox");
    var homePhone = $("#HomePhoneTextBox");
    var workPhone = $("#WorkPhoneTextBox");
    var cellPhone = $("#CellPhoneTextBox");
    var addressOne = $("#AddressOneTextBox");
    var addressTwo = $("#AddressTwoTextBox");
    var city = $("#CityTextBox");
    var state = $("#StateDropDownList");
    var zip = $("#ZipTextBox");
    var appointmentDate = $("#AppointmentDatePicker");
    var refusalReason = $("#AppointmentRefusalReasonTextArea");
    
    
    
        
    // error span variables
    var firstNameError = $("#FirstNameError");
    var middleNameError = $("#MiddleNameError");
    var lastNameError = $("#LastNameError");
    var callerIdError = $("#CallerIdError");
    var homePhoneError = $("#HomePhoneError");
    var workPhoneError = $("#WorkPhoneError");
    var cellPhoneError = $("#CellPhoneError");
    var phonesError = $("#PhonesError");
    var addressOneError = $("#AddressOneError");
    var addressTwoError = $("#AddressTwoError");
    var cityError = $("#CityError");
    var stateError = $("#StateError");
    var zipError = $("#ZipError");
    
    // div variables
    
    var appointmentTime = $("#AppointmentTimes");
    
    var mondayTime = $("#MondayTime");
    var tuesdayTime = $("#TuesdayTime");
    var wednesdayTime = $("#WednesdayTime");
    var thursdayTime = $("#ThursdayTime");
    var fridayTime = $("#FridayTime");
    var saturdayTime = $("#SaturdayTime");
    var sundayTime = $("#SundayTime");
	
    //On blur
    firstName.blur(validateFirstName);
    middleName.blur(validateMiddleName);
    lastName.blur(validateLastName);
    callerId.blur(validateCallerId);
    homePhone.blur(validateHomePhone);
    workPhone.blur(validateWorkPhone);
    cellPhone.blur(validateCellPhone);
    addressOne.blur(validateAddressOne);
    addressTwo.blur(validateAddressTwo);       
    city.blur(validateCity);
    state.blur(validateState);
    zip.blur(validateZip);
    
    appointmentDate.change(validateAppointmentDate);
    refusalReason.blur(validateRefusalReason);

    //On Submitting
    form.submit(function(){
        if(validateFirstName() & validateMiddleName() & validateLastName() & validateCallerId() & validateHomePhone() & validateWorkPhone() & validateCellPhone() & validateAddressOne() & validateAddressTwo() & validateCity() & validateState() & validateZip() & validatePhones())
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
    
    function validateCallerId(){
        var a = $("#CallerIdTextBox").val();
        var filter = /\d{10}/;
                
        if (a.length < 1) {
            callerIdError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a)){
                callerIdError.removeClass("invalid");
                return true;
            } else {
                callerIdError.text("Phone field must contain a ten digit numerical input!");
                callerIdError.removeClass("valid");
                callerIdError.addClass("invalid");
                return false;
            }
        } 
    }
    
    function validateHomePhone(){
        var a = $("#HomePhoneTextBox").val();
        var filter = /\d{10}/;
                
        if (a.length < 1) {
            homePhoneError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a)){
                homePhoneError.removeClass("invalid");
                return true;
            } else {
                homePhoneError.text("Phone field must contain a ten digit numerical input!");
                homePhoneError.removeClass("valid");
                homePhoneError.addClass("invalid");
                return false;
            }
        } 
    }
    
    function validateWorkPhone(){
        var a = $("#WorkPhoneTextBox").val();
        var filter = /\d{10}/;
                
        if (a.length < 1) {
            workPhoneError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a)){
                workPhoneError.removeClass("invalid");
                return true;
            } else {
                workPhoneError.text("Phone field must contain a ten digit numerical input!");
                workPhoneError.removeClass("valid");
                workPhoneError.addClass("invalid");
                return false;
            }
        } 
    }
    
    function validateCellPhone(){
        var a = $("#CellPhoneTextBox").val();
        var filter = /\d{10}/;
                
        if (a.length < 1) {
            cellPhoneError.removeClass("invalid");
            return true;
        } else {
            if (filter.test(a)){
                cellPhoneError.removeClass("invalid");
                return true;
            } else {
                cellPhoneError.text("Phone field must contain a ten digit numerical input!");
                cellPhoneError.removeClass("valid");
                cellPhoneError.addClass("invalid");
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
    
    function validateAppointmentDate(){  
        var date = new Date(appointmentDate.val());
        var dayOfTheWeek = date.getDay();
        
        
        
        if (appointmentDate.length != 0) {
            //appointmentTime.addClass("show");
            refusalReason.val("");
            
            if (dayOfTheWeek == 0) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("show");
                tuesdayTime.addClass("hide");
                wednesdayTime.addClass("hide");
                thursdayTime.addClass("hide");
                fridayTime.addClass("hide");
                saturdayTime.addClass("hide");
                sundayTime.addClass("hide");
            } else if (dayOfTheWeek == 1) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("hide");
                tuesdayTime.addClass("show");
                wednesdayTime.addClass("hide");
                thursdayTime.addClass("hide");
                fridayTime.addClass("hide");
                saturdayTime.addClass("hide");
                sundayTime.addClass("hide");
            } else if (dayOfTheWeek == 2) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("hide");
                tuesdayTime.addClass("hide");
                wednesdayTime.addClass("show");
                thursdayTime.addClass("hide");
                fridayTime.addClass("hide");
                saturdayTime.addClass("hide");
                sundayTime.addClass("hide");
            } else if (dayOfTheWeek == 3) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("hide");
                tuesdayTime.addClass("hide");
                wednesdayTime.addClass("hide");
                thursdayTime.addClass("show");
                fridayTime.addClass("hide");
                saturdayTime.addClass("hide");
                sundayTime.addClass("hide");
            } else if (dayOfTheWeek == 4) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("hide");
                tuesdayTime.addClass("hide");
                wednesdayTime.addClass("hide");
                thursdayTime.addClass("hide");
                fridayTime.addClass("show");
                saturdayTime.addClass("hide");
                sundayTime.addClass("hide");
            } else if (dayOfTheWeek == 5) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("hide");
                tuesdayTime.addClass("hide");
                wednesdayTime.addClass("hide");
                thursdayTime.addClass("hide");
                fridayTime.addClass("hide");
                saturdayTime.addClass("show");
                sundayTime.addClass("hide");
            } else if (dayOfTheWeek == 6) {
                mondayTime.removeClass("show");
                tuesdayTime.removeClass("show");
                wednesdayTime.removeClass("show");
                thursdayTime.removeClass("show");
                fridayTime.removeClass("show");
                saturdayTime.removeClass("show");
                sundayTime.removeClass("show");
                
                mondayTime.addClass("hide");
                tuesdayTime.addClass("hide");
                wednesdayTime.addClass("hide");
                thursdayTime.addClass("hide");
                fridayTime.addClass("hide");
                saturdayTime.addClass("hide");
                sundayTime.addClass("show");
            }
        }
    }
    
    function validateRefusalReason(){ 
        if (refusalReason.val() != 0) {
            appointmentDate.val("");
            appointmentTime.removeClass("show");
            appointmentTime.addClass("hide");
            
            mondayTime.removeClass("show");
            tuesdayTime.removeClass("show");
            wednesdayTime.removeClass("show");
            thursdayTime.removeClass("show");
            fridayTime.removeClass("show");
            saturdayTime.removeClass("show");
            sundayTime.removeClass("show");
                
            mondayTime.addClass("hide");
            tuesdayTime.addClass("hide");
            wednesdayTime.addClass("hide");
            thursdayTime.addClass("hide");
            fridayTime.addClass("hide");
            saturdayTime.addClass("hide");
            sundayTime.addClass("hide");
        }

    }
});

/*
 * Jquery section
 */


  
  


