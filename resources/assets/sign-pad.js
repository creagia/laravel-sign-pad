import SignaturePad from 'signature_pad';

const eSignpad = document.getElementById('e-signpad');

let signaturePad = new SignaturePad(eSignpad.querySelector('canvas')),
    submit = document.getElementById('sign-pad-button-submit'),
    clear = document.getElementById('sign-pad-button-clear');

submit.addEventListener('click', function () {
    document.getElementById('sign').value = signaturePad.toDataURL();
});

clear.addEventListener('click', function () {
    signaturePad.clear();
})

let resizeCanvas = () => {

    let canvas = document.getElementById('e-signpad').querySelector('canvas');

    if (canvas.width > window.innerWidth) {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
}

document.addEventListener('DOMContentLoaded', function (event) {
    resizeCanvas()
});

window.addEventListener("resize", function () {
    resizeCanvas()
});