import SignaturePad from 'signature_pad';

const eSignpads = document.querySelectorAll('.e-signpad');

let submitted = false;

eSignpads.forEach(function(eSignpad) {
    let signaturePad = new SignaturePad(eSignpad.querySelector('canvas')),
        submit = eSignpad.querySelector('.sign-pad-button-submit'),
        clear = eSignpad.querySelector('.sign-pad-button-clear');

    submit.addEventListener('click', function (event) {
        if (submitted) {
            event.preventDefault();
        }
        submitted = true;
        eSignpad.querySelector('.sign').value = signaturePad.toDataURL();
    });

    clear.addEventListener('click', function () {
        signaturePad.clear();
    })

});

let resizeCanvas = () => {
    document.querySelectorAll('.e-signpad').forEach(function(eSignPad) {
        let canvas = eSignPad.querySelector('canvas');

        if (canvas.width > window.innerWidth) {
            let signaturePad = new SignaturePad(eSignPad.querySelector('canvas'));
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
    });
}

document.addEventListener('DOMContentLoaded', function (event) {
    resizeCanvas()
});

window.addEventListener("resize", function () {
    resizeCanvas()
});