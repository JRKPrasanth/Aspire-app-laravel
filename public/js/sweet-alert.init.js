/**
 * Theme: Appzia Admin
 * SweetAlert
 */

! function(e) {
    "use strict";
    var t = function() {};
    t.prototype.init = function() {


/****************** Success message **************************/
        e("#sa-success").on("click", function() {
            swal(

              {
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "success",
                timer: 2e3
              }

            )
        }),


/******************** Warning message *********************/
         e("#sa-warning").on("click", function() {
            swal(

              {
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                timer: 2e3
              }
            )
        })
/******************* Delete message ************************/
        , e("#sa-params").on("click", function() {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: !1,
                //timer: 2e3,
                closeOnCancel: !1
            }, function(e) {
                e ? swal("Deleted!", "Your imaginary file has been deleted.", "success") : swal("Cancelled", "Your imaginary file is safe :)", "error")
            })
        }),
/****************** Already Exists *************************/
        e("#sa-close").on("click", function() {
            swal({
                title: "Already Exists!",
                text: "This data is already exists.",
                timer: 2e3,
                showConfirmButton: !1
            })
        })




        
    }, e.SweetAlert = new t, e.SweetAlert.Constructor = t
}(window.jQuery),
function(e) {
    "use strict";
    e.SweetAlert.init()
}(window.jQuery);
