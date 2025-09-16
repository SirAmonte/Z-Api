(function () {
   "use strict";
   /*jslint browser: true*/
   /*jslint devel: true*/
   let baseApiAddress = "https://teomanliman.be/zapi/api2/";
   /* Vorige lijn aanpassen naar de locatie op jouw domein! */

   let alertEl = document.getElementById("alert");
   let opties = {
      method: "POST", // *GET, POST, PUT, DELETE, etc.
      mode: "cors", // no-cors, *cors, same-origin
      cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
      credentials: "omit" // include, *same-origin, omit
      /* Opgelet : volgende headers niet toevoegen :
          JSON triggert de pre-flight mode, waardoor de toegang op
          deze manier niet meer zal lukken. Tenzij daar in de API expliciet
        rekening is met gehouden ...
      */
      /*, headers: {
         "Content-Type": "application/json",
         "Accept": "application/json"
      }*/
   };

   function getAccounts() {
      let url = baseApiAddress + "accounts/";
      opties.method = "GET";
      opties.body = null;

      return fetch(url, opties)
         .then(response => {
            if (!response.ok) {
               console.log(response.status);
            }
            return response.json();
         })
         .then(responseData => {
            return responseData.data;
         })
         .catch(error => {
            alerter("Error: " + error);
            throw error;
         });
   }


   async function voegGebruiker() {
      console.log("ok");
      try {
         let accounts = await getAccounts();
         console.log(accounts);
         let username = document.getElementById("fusername").value;
         for (let i = 0; i < accounts.length; i++) {
            console.log(`account: ${accounts[i].username}, username: ${username}`)
            if (accounts[i].username === username) {
               alerter("username already exists");
               return;
            }
         }
         let url = baseApiAddress + "accounts/";
         opties.method = "POST";
         opties.body = JSON.stringify({
            username: document.getElementById("fusername").value,
            email: document.getElementById("femail").value,
            password: document.getElementById("fpassword").value
         });

         console.log(document.getElementById("fusername").value)
         console.log(document.getElementById("femail").value)
         console.log(document.getElementById("fpassword").value)

         fetch(url, opties)
            .then(response => {
               if (!response.ok) {
                  console.log(response.status);
               }
               return response.json();
            })
            .catch(error => {
               console.log(error);
            })
      }
      catch (error) {
         console.log(error);
      }
   }

   async function deleteGebruiker() {
      let url = baseApiAddress + "accounts/";
      opties.method = "DELETE";
      opties.body = JSON.stringify({
         account_id: document.getElementById("fid").value
      });
      const response = await fetch(url, opties);
      console.log(response)
      try {
         const responseData = await response.json();
         console.log(responseData.data);
      } catch (error) {
         // verwerk de fout
         alertEl.innerHTML = "fout : " + error;
      };
   }

   async function updateAccount() {
      let url = baseApiAddress + "accounts/";
      opties.method = "PUT";
      opties.body = JSON.stringify({
         username: document.getElementById("fbusername").value,
         email: document.getElementById("fbemail").value,
         password: document.getElementById("fbpassword").value,
         profilephoto: document.getElementById("fbprofilephoto").value,
         bio: document.getElementById("fbbio").value,
         account_id: document.getElementById("fbid").value
      });
      console.log(document.getElementById("fbprofilephoto").value,)
      const response = await fetch(url, opties);
      console.log(response)
      try {
         const responseData = await response.json();
         console.log(responseData.data);
      } catch (error) {
         // verwerk de fout
         alertEl.innerHTML = "fout : " + error;
      };
   }


   function getGebruiker() {
      let url = baseApiAddress + "login/";
      // dit endpoint verwacht dat je dit met POST aanspreekt
      opties.method = "POST"
      opties.body = JSON.stringify({
         username: document.getElementById("flusername").value,
         password: document.getElementById("flpassword").value
      });

      console.log(document.getElementById("flusername").value);
      console.log(document.getElementById("flpassword").value)
      fetch(url, opties)
         .then(function (response) {
            console.log('response');
            return response.json();
         })
         .then(function (responseData) {
            // test status van de response
            if (responseData.status < 200 || responseData.status > 299) {
               // login faalde, boodschap weergeven
               // Hier kan je ook een groter onderscheid maken tussen de verschillende vormen van login falen.
               // alerter("Login mislukt : deze naam/paswoord combinatie bestaat niet");
               // return, zodat de rest van de fetch niet verder uitgevoerd wordt
               return;
            }
            console.log(responseData);
            // de verwerking van de data
            var list = responseData.data;

            if (list.length > 0) {
               // list bevat minstens 1 itemproperty met waarde
               // we nemen het eerste
               console.log(list);
               alerter(`welcome: ${list[0].username}`)
            } else {
               alerter("Login failed : this login/password combination does not exist");
            }

         })
         .catch(function (error) {
            console.log(error);
            // verwerk de fout
            // alertEl.innerHTML = "fout : " + error;
         });
   }

   async function changePassword() {
      let url = baseApiAddress + "login/";
      opties.method = "PUT";
      opties.body = JSON.stringify({
         email: document.getElementById("flemail").value,
         password: document.getElementById("flnpassword").value,
      });
      console.log(document.getElementById("flemail").value);
      console.log(document.getElementById("flnpassword").value)
      const response = await fetch(url, opties);
      console.log(response)
      try {
         const responseData = await response.json();
         console.log(responseData.data);
      } catch (error) {
         // verwerk de fout
         alertEl.innerHTML = "fout : " + error;
      };
   }

   function getFollowers() {
      let url = baseApiAddress + "followers/";
      opties.method = "GET";
      opties.body = null;

      fetch(url, opties)
         .then(response => {
            if (!response.ok) {
               console.log(response.status);
            }
            return response.json();
         })
         .then(responseData => {
            console.log(responseData.data);
            return responseData.data;
         })
         .catch(error => {
            alerter("Error: " + error);
            throw error;
         });
   }

   async function addFollowers() {
      let url = baseApiAddress + "followers/";
      opties.method = "POST";
      opties.body = JSON.stringify({
         follower_id: document.getElementById("fgollower_id").value,
         following_id: document.getElementById("fgollowing_id").value,
      });

      const response = await fetch(url, opties);
      console.log(response)
      try {
         const responseData = await response.json();
         console.log(responseData.data);
      } catch (error) {
         // verwerk de fout
         alertEl.innerHTML = "fout : " + error;
      };
   }

   async function wijzigFollowers() {
      let url = baseApiAddress + "followers/";
      opties.method = "PUT";
      opties.body = JSON.stringify({
         follower_id: document.getElementById("fgollower_id").value,
         following_id: document.getElementById("fgollowing_id").value,
         is_blocked: document.getElementById("fblocked").value
      });

      const response = await fetch(url, opties);
      console.log(response)
      try {
         const responseData = await response.json();
         console.log(responseData.data);
      } catch (error) {
         // verwerk de fout
         alertEl.innerHTML = "fout : " + error;
      };
   }

   async function verwijderFollower() {
      let url = baseApiAddress + "followers/";
      opties.method = "DELETE";
      opties.body = JSON.stringify({
         follower_id: document.getElementById("fgollower_id").value,
         following_id: document.getElementById("fgollowing_id").value
      });
      const response = await fetch(url, opties);
      console.log(response)
      try {
         const responseData = await response.json();
         console.log(responseData.data);
      } catch (error) {
         // verwerk de fout
         alertEl.innerHTML = "fout : " + error;
      };
   }

   // EventListeners
   document.getElementById("btnTestLogin").addEventListener("click", function (e) {

   });

   document.getElementById("btnAddAccount").addEventListener("click", function (e) {
      e.preventDefault();
      console.log("ok");
      voegGebruiker();
   });

   document.getElementById("btnLogin").addEventListener("click", function (e) {
      e.preventDefault();
      console.log("get");
      getGebruiker();
   });

   document.getElementById("btnGetTijd").addEventListener("click", function () {
      getApiTijd();
   });

   document.getElementById("btnGetProducten").addEventListener("click", function () {
      console.log("ok");
      getAccounts();
   });

   document.getElementById("btnVerwijderAccount").addEventListener("click", function (e) {
      e.preventDefault();
      deleteGebruiker();
   });

   document.getElementById("btnBewerkAccount").addEventListener("click", function (e) {
      e.preventDefault();
      updateAccount();
   });

   document.getElementById("btnUpdatePassword").addEventListener("click", function (e) {
      e.preventDefault();
      changePassword();
   })

   document.getElementById('btnGetFollowers').addEventListener("click", function (e) {
      e.preventDefault();
      verwijderFollower();
   })

   // helper functies
   function alerter(message) {
      alertEl.innerHTML = message;
   }
})();

