(function($) {
    "use strict";
  
    /*--------------------------
    preloader
    ---------------------------- */
    $(window).on('load', function() {
      var pre_loader = $('#preloader');
      pre_loader.fadeOut('slow', function() {
        $(this).remove();
      });
    });
  
    /*---------------------
     TOP Menu Stick
    --------------------- */
    var s = $("#sticker");
    var pos = s.position();
    $(window).on('scroll', function() {
      var windowpos = $(window).scrollTop() > 300;
      if (windowpos > pos.top) {
        s.addClass("stick");
      } else {
        s.removeClass("stick");
      }
    });
  
    /*----------------------------
     Navbar nav
    ------------------------------ */
    var main_menu = $(".main-menu ul.navbar-nav li ");
    main_menu.on('click', function() {
      main_menu.removeClass("active");
      $(this).addClass("active");
    });
  
    /*----------------------------
     wow js active
    ------------------------------ */
    new WOW().init();
  
    $(".navbar-collapse a:not(.dropdown-toggle)").on('click', function() {
      $(".navbar-collapse.collapse").removeClass('in');
    });
  
    
    
  
})(jQuery);



// select all values from recruitment form
let companyName = document.querySelector("input[name='name']");
let companySite = document.querySelector("input[name='website']");
let contactEmail = document.querySelector("input[name='email']");
let contactNumber = document.querySelector("input[name='telephone']");
let devJobTitle = document.querySelector("input[name='jobtitle']");
let numberOfDevs = document.querySelector("input[name='devnumber']");
let devSkillset = document.querySelector("input[name='skillset']");
let devExperience = document.querySelector("input[name='devexperience']");
let duration = document.querySelector("input[name='duration']");
let startDate = document.querySelector("input[name='startdate']");
let comments = document.querySelector("input[name='comments']");

let formData = {

}

const formButton = document.querySelector('.recruit-form-button')
formButton.addEventListener('click', async(e)=>{

    //prevent default behaviour of refreshing
    e.preventDefault();
    formData.companyName = companyName.value
    formData.companySite = companySite.value
    formData.contactEmail = contactEmail.value
    formData.contactNumber = contactNumber.value
    formData.devJobTitle = devJobTitle.value
    formData.numberOfDevs = numberOfDevs.value
    formData.devSkillset = devSkillset.value
    formData.devExperience = devExperience.value
    formData.duration = duration.value
    formData.startDate = startDate.value
    formData.comments = comments.value
    console.log(formData)

    let emailReg = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let email = contactEmail.value;
    // form validation
    if(companyName.value === ''){
        alert('Please fill in company name')
        return false
    }
    else if(!emailReg.test(email)){
        alert('Please enter a valid email')
        return false
    }
    else if(companySite.value === ''){
        alert('Please enter a valid url')
        return false
    }
    else if(contactNumber.value.length < 10 || contactNumber.value === ''){
        alert('Please check that phone number is valid')
        return false
    }
    // else if(devJobTitle.value || numberOfDevs.value || 
    //         devSkillset.value || devExperience.value || 
    //         duration.value || startDate.value === ''){
    //     alert('Please fill in blank spaces')
    //     return false
    // }
    else if(devJobTitle.value === ''){
        alert('Please enter developer job title')
        return false
    }
    else if(numberOfDevs.value === ''){
        alert('Please enter number of developers')
        return false
    }
    else if(devSkillset.value === ''){
        alert('Please enter skillset of developers')
        return false
    }
    else if(devExperience.value === ''){
        alert('Please enter developer years of experience')
        return false
    }
    else if(duration.value === ''){
        alert('Please specify duration')
        return false
    }
    else if(startDate.value === ''){
        alert('Please specify date of commencement')
        return false
    }

    else{
        //post request submitting form data
        await fetch(
            "https://cors-anywhere.herokuapp.com/http://167.99.82.56:7000/api/send_mail",
            {
                headers: {
                    "Content-Type": "application/json; charset=UTF-8",
                    "Access-Control-Allow-Origin": '*'
                },
                method: "POST",
                body: JSON.stringify(formData),
            }
        )
        .then((res) => res.json())
        .then((data) => {
            alert(data.msg)
        })
        .catch((err) => console.log(err));
    }
})

