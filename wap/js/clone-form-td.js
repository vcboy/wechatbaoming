/*
Author: Tristan Denyer (based on Charlie Griefer's original clone code, and some great help from Dan - see his comments in blog post)
Plugin repo: https://github.com/tristandenyer/Clone-section-of-form-using-jQuery
Demo at http://tristandenyer.com/using-jquery-to-duplicate-a-section-of-a-form-maintaining-accessibility/
Ver: 0.9.5.0
Last updated: Oct 23, 2015

The MIT License (MIT)

Copyright (c) 2011 Tristan Denyer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
$(function () {
    //console.log('clone td');
    init();

     function init(){
        var newElem1 = $('#template').clone().html(); 
        newElem = newElem1.replace(/{n}/g,1);
        $(newElem).appendTo('#clonetd');
        $('#datetimes_edu1').focus();

        
        var newElem2 = $('#template_work').clone().html(); 
        newElem = newElem2.replace(/{n}/g,1);
        $(newElem).appendTo('#clonetd_work');
        $('#company').focus();
    };
    //教育经历
    $('#btnAdd').click(function () {
        //console.log('clone td');
        var num     = $('.divedu').length, // Checks to see how many "duplicatable" input fields we currently have
            newNum  = new Number(num),      // The numeric ID of the new input field being added, increasing by 1 each time
            newElem = $('#entry' + num).clone().attr('id', 'entry' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value
    
        var newElem1 = $('#template').clone().html();

        newElem = newElem1.replace(/{n}/g,newNum);

        $(newElem).appendTo('#clonetd');
        $('#datetimes_edu' + newNum  ).focus();

        // Enable the "remove" button. This only shows once you have a duplicated section.
        $('#btnDel').attr('disabled', false);

        // Right now you can only add 4 sections, for a total of 5. Change '5' below to the max number of sections you want to allow.
        if (newNum == 5)
        $('#btnAdd').attr('disabled', true).prop('value', "You've reached the limit"); // value here updates the text in the 'add' button when the limit is reached 
    });

    $('#btnDel').click(function () {
    // Confirmation dialog box. Works on all desktop browsers and iPhone.
        if (confirm("移除将无法恢复,确定移除?"))
        {
            var num = $('.divedu').length - 1;
            console.log(num);
            // how many "duplicatable" input fields we currently have
            $('#entry' + num).slideUp('slow', function () {
                $(this).remove();
                // if only one element remains, disable the "remove" button
                if (num -1 === 1)
                    $('#btnDel').attr('disabled', true);
                // enable the "add" button
                $('#btnAdd').attr('disabled', false).prop('value', "add section");
            });
        }
        return false; // Removes the last section you added
    });
    // Enable the "add" button
    $('#btnAdd').attr('disabled', false);
    // Disable the "remove" button
    $('#btnDel').attr('disabled', true);



    //工作经历
    $('#btnAdd_work').click(function () {
        //console.log('clone td');
        var num     = $('.divwork').length, // Checks to see how many "duplicatable" input fields we currently have
            newNum  = new Number(num),      // The numeric ID of the new input field being added, increasing by 1 each time
            newElem = $('#entry' + num).clone().attr('id', 'entry' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value
    
        var newElem1 = $('#template_work').clone().html();

        newElem = newElem1.replace(/{n}/g,newNum);

        $(newElem).appendTo('#clonetd_work');
        $('#datetimes_work' + newNum  ).focus();

        // Enable the "remove" button. This only shows once you have a duplicated section.
        $('#btnDel_work').attr('disabled', false);

        // Right now you can only add 4 sections, for a total of 5. Change '5' below to the max number of sections you want to allow.
        if (newNum == 5)
        $('#btnAdd_work').attr('disabled', true).prop('value', "You've reached the limit"); // value here updates the text in the 'add' button when the limit is reached 
    });

    $('#btnDel_work').click(function () {
    // Confirmation dialog box. Works on all desktop browsers and iPhone.
        if (confirm("移除将无法恢复,确定移除?"))
        {
            var num = $('.divwork').length - 1;
            console.log(num);
            // how many "duplicatable" input fields we currently have
            $('#entry_work' + num).slideUp('slow', function () {
                $(this).remove();
                // if only one element remains, disable the "remove" button
                if (num -1 === 1)
                    $('#btnDel_work').attr('disabled', true);
                // enable the "add" button
                $('#btnAdd_work').attr('disabled', false).prop('value', "add section");
            });
        }
        return false; // Removes the last section you added
    });
    // Enable the "add" button
    $('#btnAdd_work').attr('disabled', false);
    // Disable the "remove" button
    $('#btnDel_work').attr('disabled', true);
});