/*
Template Name: Material Pro admin
Author: Wrappixel
Email: niravjoshi87@gmail.com
File: js
*/

//TOAST
var toastMessage = ''; //message to be shown in toast
var default_class = 'virtual_button ' //class that defines the button class to trigger the toast
var custom_class = ''; //to store the class that defines the toast color
var virtual_class = default_class + custom_class;

function showToast()
{
     $.toast({
          heading: 'Alert',
          text: toastMessage,
          position: 'top-right',
          icon: toastType,
          hideAfter: 3000, 
          stack: 6
     });
}

$(function() {
     "use strict";
     $(".virtual_button").click(function(){
          $.toast({
               heading: toastMessage,
               text: '',
               position: 'top-right',
               loaderBg:'#ff6849',
               icon: 'success',
               hideAfter: 3000, 
               stack: 6
          });
          
     });
     
     $(".tst2").click(function(){
          $.toast({
               heading: 'Welcome to Material Pro admin',
               text: 'Use the predefined ones, or specify a custom position object.',
               position: 'top-right',
               loaderBg:'#ff6849',
               icon: 'warning',
               hideAfter: 3500, 
               stack: 6
          });
          
     });
     $(".tst3").click(function(){
          $.toast({
               heading: 'Welcome to Material Pro admin',
               text: 'Use the predefined ones, or specify a custom position object.',
               position: 'top-right',
               loaderBg:'#ff6849',
               icon: 'success',
               hideAfter: 3500, 
               stack: 6
          });
          
     });
     
     $(".tst4").click(function(){
          $.toast({
               heading: 'Welcome to Material Pro admin',
               text: 'Use the predefined ones, or specify a custom position object.',
               position: 'top-right',
               loaderBg:'#ff6849',
               icon: 'error',
               hideAfter: 3500
               
          });
          
     });
});

