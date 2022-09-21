import SignaturePad from 'signature_pad';
const eSignpad = document.getElementById('e-signpad');

let signaturePad = new SignaturePad(eSignpad.querySelector('canvas')),
    submit = document.getElementById('sign-pad-button-submit'),
    clear = document.getElementById('sign-pad-button-clear')

submit.addEventListener('click', function () {
    document.getElementById('sign').value = signaturePad.toDataURL()
});

clear.addEventListener('click', function () {
    signaturePad.clear();
})
