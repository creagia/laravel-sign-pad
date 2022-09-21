<div id="e-signpad">
    <canvas style="touch-action: none; border: 1px solid {{ $borderColor }}"
            width="{{ $width }}"
            height="{{ $height }}"
            class="{{ $padClasses }}"></canvas>
    <input type="hidden" name="sign" id="sign">
    <div>
        <button id="sign-pad-button-clear" type="button" class="{{$buttonClasses}}"> Clear</button>
        <button id="sign-pad-button-submit" type="submit" class="{{$buttonClasses}}"> Submit</button>
    </div>
</div>

