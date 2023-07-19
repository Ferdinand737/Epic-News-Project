var var1 = setInterval(autoRefresh,10000)

function autoRefresh(){
   if($('.postComponent').length){
      
      if($('#postList').length){
         $.ajax({
            url:"api.php?page=home",
            type:"GET",
            dataType: "html",
            success: function(data){
              $('#postList').html(data)
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus + ": " + errorThrown)
           }
         })
         
      }else{
         var id = $('.postComponent').attr('id')
         $.ajax({
            url:"api.php?page=post&content=post&id="+id,
            type:"GET",
            dataType: "html",
            success: function(data){
            $('#secPost').html(data)
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus + ": " + errorThrown)
           }
         })
   
         $.ajax({
            url:"api.php?page=post&content=comments&id="+id,
            type:"GET",
            dataType: "html",
            success: function(data){
            $('#refreshComments').html(data)
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus + ": " + errorThrown)
           }
         })
      }
   }
}

function vote(id,button,type){
   let data = {}
   let fields

   fields = $("form#" + type + "-vote-" + id + " input[type='hidden']")

   fields.each(function () {
      let name = $(this).attr('name')
      let value = $(this).val()
      data[name] = value
   })

   data[button] = 1

   $.ajax({
      url:"Vote.php",
      type:"POST",
      dataType: "json",
      data:data,
      success: function (response) {
        
      },
     error: function () {
        window.location.href = "loginPage.php"
      }
      
   })
   if(window.location.href.includes("replyPage")){
      window.location.reload()
      return
   }
   autoRefresh()
}


function validateLogin(){
   //alert("it worked")
}

function validateRegister(e){
   let passRegex = new RegExp("^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]{8,}$")
   let emailRegex = new RegExp("^((([!#$%&'*+\\-/=?^_`{|}~\\w])|([!#$%&'*+\\-/=?^_`{|}~\\w][!#$%&'*+\\-/=?^_`{|}~\\.\\w]{0,}[!#$%&'*+\\-/=?^_`{|}~\\w]))[@]\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*)$")
   let inputEmail = $('#register-email').val()
   let inputPassword = $('#register-password').val()

   if(!emailRegex.test(inputEmail)){
      alert("Please enter a valid email")
      e.preventDefault()
      return
   }

   if(!passRegex.test(inputPassword)){
      alert("Invalid password.\nMust have:\n8 characters\n1 letter\n1 number\nno symbols\nExample: password123")
      e.preventDefault()
      return
   }
}

function validateForgotEmail(e){
   let emailRegex = new RegExp("^((([!#$%&'*+\\-/=?^_`{|}~\\w])|([!#$%&'*+\\-/=?^_`{|}~\\w][!#$%&'*+\\-/=?^_`{|}~\\.\\w]{0,}[!#$%&'*+\\-/=?^_`{|}~\\w]))[@]\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*)$")
   let inputEmail = $('#forgot-email').val()
   if(!emailRegex.test(inputEmail)){
      alert("Please enter a valid email")
      e.preventDefault()
      return
   }
}

function validateResetPassword(e){
   let passRegex = new RegExp("^(?=.*[A-Za-z])(?=.*\\d)[A-Za-z\\d]{8,}$")
   let inputPassword = $('#new-password').val()
   if(!passRegex.test(inputPassword)){
      alert("Invalid password.\nMust have:\n8 characters\n1 letter\n1 number\nno symbols\nExample: password123")
      e.preventDefault()
      return
   }
}



function getTrafficData(type){
   return new Promise((resolve, reject) => {
      $.ajax({
        url: "api.php?chart="+type,
        type: "GET",
        dataType: "json",
        success: function (response) {
          resolve({
            counts: response.counts,
            dates: response.dates
          });
        },
        error: function (jqXHR, textStatus, errorThrown) {
          reject(textStatus + ": " + errorThrown);
        },
      });
    });
}


document.addEventListener('DOMContentLoaded',async function() {
   charts = ["post","user","comment"]

   for(const chart of charts){
      canvas = document.getElementById(chart+'-chart')
      if(canvas){
   
         const { counts, dates } = await getTrafficData(chart);
       
         var ctx = document.getElementById(chart+'-chart').getContext('2d');
         var myChart = new Chart(ctx, {
         type: 'bar',
         data: {
           labels: dates,
           datasets: [
             {
               label: chart+'s per Day',
               data: counts,
               backgroundColor: 'rgba(75, 192, 192, 0.2)',
               borderColor: 'rgba(75, 192, 192, 1)',
               borderWidth: 1,
             },
           ],
         },
         options: {
           responsive: false,
           scales: {
             y: {
               beginAtZero: true,
             },
           },
         },
       });
      }

   }
});