import SignaturePad from 'signature_pad';

const eSignpads = document.querySelectorAll('.e-signpad');

let submitted = false;

eSignpads.forEach(function(eSignpad) {
    let signaturePad = new SignaturePad(eSignpad.querySelector('canvas')),
        submit = eSignpad.querySelector('.sign-pad-button-submit'),
        clear = eSignpad.querySelector('.sign-pad-button-clear'),
        disabledWithoutSignature = parseInt(eSignpad.getAttribute('data-disabled-without-signature'));

    signaturePad.addEventListener("beginStroke", () => {
        if(disabledWithoutSignature) {
            submit.removeAttribute('disabled');
        }
    });

    submit.addEventListener('click', (event) => {
        if (submitted) {
            event.preventDefault();
        }
        submitted = true;
        eSignpad.querySelector('.sign').value = signaturePad.toDataURL();
    });

    clear.addEventListener('click', () => {
        signaturePad.clear();
        if(disabledWithoutSignature) {
            submit.setAttribute('disabled', 'disabled');
        }
    })

});

let resizeCanvas = () => {
    document.querySelectorAll('.e-signpad').forEach(function(eSignpad) {
        let canvas = eSignpad.querySelector('canvas'),
            submit = eSignpad.querySelector('.sign-pad-button-submit'),
            disabledWithoutSignature = parseInt(eSignpad.getAttribute('data-disabled-without-signature'));

        if (canvas.width > window.innerWidth) {
            let signaturePad = new SignaturePad(eSignpad.querySelector('canvas'));
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            if(disabledWithoutSignature) {
                submit.setAttribute('disabled', 'disabled');
            }

            signaturePad.addEventListener("beginStroke", () => {
                if(disabledWithoutSignature) {
                    submit.removeAttribute('disabled');
                }
            });

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