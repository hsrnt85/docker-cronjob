// $(function () {

//     window.Parsley.addValidator('fileextension', function (value, requirement) {
//             var fileExtension = value.split('.').pop();
//             var req = requirement.split('|');

//             return req.includes(fileExtension);
//         }, 32)
//         .addMessage('ms', 'fileextension', 'Fail ini tidak dibenarkan');

//     window.Parsley.addValidator('maxFileSize', {
//             validateString: function(_value, maxSize, parsleyInstance) {
//             if (!window.FormData) {
//                 return true;
//             }
//                 var files = parsleyInstance.$element[0].files;
//                 return files.length != 1  || files[0].size <= maxSize * 1024;
//             },
//             requirementType: 'integer',
//     });

// });