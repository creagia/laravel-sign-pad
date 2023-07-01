<div class="e-signpad" data-disabled-without-signature="{{ $disabledWithoutSignature }}" style="display: flex; flex-direction: column; align-items: center">
    <canvas style="touch-action: none; border: 1px solid {{ $borderColor }}; max-width: 100%"
            width="{{ $width }}"
            height="{{ $height }}"
            class="{{ $padClasses }}"></canvas>
    <div>
        <input type="hidden" name="sign" class="sign">
        <button type="button" class="sign-pad-button-clear {{$buttonClasses}}">{!! $clearName !!}</button>
        <button type="submit" class="sign-pad-button-submit {{$buttonClasses}}" {{ $disabledWithoutSignature ? 'disabled' : '' }}>{!! $submitName !!}</button>
    </div>
</div>

