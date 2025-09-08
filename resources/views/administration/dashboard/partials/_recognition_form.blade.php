<style>
    .recognition-reminder-wrapper { display: flex; justify-content: center; align-items: center; }
    .recognition-reminder { position: relative; max-width: 620px; width: 100%; background: #f2f1fe; border-radius: 16px; padding: 20px 24px; box-shadow: 0 8px 24px rgba(120, 109, 240, 0.25); border: 1px solid rgba(120, 109, 240, 0.25); min-height: 100px; }
    .recognition-reminder .rr-row { display: grid; grid-template-columns: 48px 1fr auto; gap: 12px; align-items: center; }
    .recognition-reminder .rr-icon { width: 48px; height: 48px; border-radius: 12px; background: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
    .recognition-reminder .rr-text { color: #3d2db5; font-weight: 700; font-size: 18px; line-height: 1.4; }
    .recognition-reminder .rr-cta { background: #786df0; color: #fff; border: none; border-radius: 9999px; padding: 10px 16px; font-weight: 700; font-size: 14px; white-space: nowrap; }
    .recognition-reminder .rr-bell { position: absolute; right: -8px; bottom: -8px; width: 36px; height: 36px; background: #f8f400; border-radius: 10px; box-shadow: 0 6px 14px rgba(0,0,0,0.15); }
    .recognition-reminder .rr-bell::before { content: ''; position: absolute; top: -10px; left: 50%; width: 2px; height: 10px; background: #c7c3fb; transform: translateX(-50%); border-radius: 1px; }
    .recognition-reminder .rr-bell::after { content: ''; position: absolute; right: -6px; top: -6px; width: 14px; height: 14px; background: #ff4d4d; border-radius: 50%; box-shadow: 0 0 0 2px #f2f1fe; }
    .recognition-reminder .rr-bell i { font-size: 40px; display: inline-block; transform-origin: top center; animation: bell-swing 2.2s ease-in-out infinite; }
    @keyframes bell-swing { 0% { transform: rotate(0deg); } 20% { transform: rotate(12deg); } 40% { transform: rotate(-10deg); } 60% { transform: rotate(8deg); } 80% { transform: rotate(-6deg); } 100% { transform: rotate(0deg); } }
    @media (max-width: 576px) { .recognition-reminder .rr-row { grid-template-columns: 40px 1fr; } .recognition-reminder .rr-cta { grid-column: span 2; justify-self: center; margin-top: 8px; } }
</style>

<div class="row mb-4">
    <div class="col-12 recognition-reminder-wrapper mx-auto">
        <div class="recognition-reminder">
            <div class="rr-row">
                <div class="rr-icon">
                    <svg width="30" height="30" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2l2.39 4.84L20 7.27l-3.91 3.81.92 5.36L12 14.77l-5.01 2.67.92-5.36L4 7.27l5.61-.43L12 2z" fill="#786df0"/>
                    </svg>
                </div>
                <div class="rr-text">You've not recognized anyone in the last 15 days.</div>
                @if(isset($canRecognize) && $canRecognize)
                    <button type="button" class="rr-cta" data-bs-toggle="modal" data-bs-target="#recognitionModal">Recognize Now!!</button>
                @else
                    <button type="button" class="rr-cta" disabled>Recognize Now!!</button>
                @endif
            </div>
            <div class="rr-bell"><i class="ti ti-bell text-warning"></i></div>
        </div>
    </div>
</div>
