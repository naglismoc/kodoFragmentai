import {cardEvent} from "./cardsButtons.js";
import {sort} from './sort.js';
import {filterBar} from './filterBar.js';
import {burgerMenu} from './burgerMenu.js';
import {yHandler} from './scroll.js';
import {userInfo} from './userInfo.js'
import {modalImages} from './modal.js'
import { isEmpty } from "lodash";



// ----------- axios js fetchinimui -----------------//
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



//============= Basic funktionalumas ================//


// ------tai kas pasileidžia užsikrovus puslapiui ----//

    userInfo();
    burgerMenu();
    modalImages(); 
    cardEvent();
    filterBar();

  let ui = document.querySelector('#ui')

    
  if(ui != null) {


    document.getElementById("data").addEventListener("scroll", yHandler);


      // ---------- Sort listeneris----------------------//
    let sorts  =  document.querySelectorAll('.sort');
    for (let i = 0; i < sorts.length; i++) {
        sorts[i].addEventListener('click', function (){
            sort();
        });
        
    }

    document.querySelector('#city').addEventListener('change', function (){
        sort();
    });

    document.querySelector('#distance').addEventListener('focusout', function (){
        sort();
    });


  sorts  =  document.querySelectorAll('.round_blue_x');
  for (let i = 0; i < sorts.length; i++) {
      //  console.log("la");  !!!
      sorts[i].addEventListener('click', function (){
          sort();
      });
      
  }
    
    var typingTimer;                //timer identifier
    var doneTypingInterval = 500;  //time in ms, 0.5 second 
    var distance = document.querySelector('#distance');

    //on keyup, start the countdown
    distance.addEventListener('keyup', function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown 
    distance.addEventListener('keydown', function () {
      clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping () {
        sort();
    }
  }

  