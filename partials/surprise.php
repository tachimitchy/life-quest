<div class="sx" id="sx" hidden>
    <div class="sx-petals" id="sxPetals" aria-hidden="true"></div>

    <!-- Step 1: locked gift -->
    <section class="sx-step" id="sxLock">
        <p class="sx-eyebrow">A little surprise for you</p>

        <div class="sx-gift" id="sxGift" aria-hidden="true">
            <span class="sx-lid"></span>
            <span class="sx-body"></span>
            <span class="sx-glow"></span>
        </div>

        <p class="sx-label">Enter the 4-digit code to open it</p>
        <div class="sx-code" id="sxCode">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 1" autocomplete="off">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 2" autocomplete="off">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 3" autocomplete="off">
            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" aria-label="Digit 4" autocomplete="off">
        </div>
        <p class="sx-error" id="sxError" role="alert" hidden>Hmm, that's not it. Try again 💗</p>
        <p class="sx-hint" id="sxHint" hidden></p>
    </section>

    <!-- Step 2: bouquet -->
    <section class="sx-step" id="sxBouquetStep" hidden>
        <h2 class="sx-title" id="sxTitle"></h2>
        <p class="sx-label">Tap each tulip 🌷</p>
        <div class="sx-bouquet" id="sxBouquet"></div>

        <!-- Appears after every tulip has been opened -->
        <div class="sx-end" id="sxEnd" hidden>
            <p class="sx-final" id="sxFinal"></p>
            <button type="button" class="sx-letter-btn" id="sxLetterBtn">Read my letter 💌</button>
            <!-- Appears only after the letter has been read -->
            <button type="button" class="sx-back" id="sxBack" hidden>Back to Life Quest 💗</button>
        </div>
    </section>

    <!-- Tulip message + photo popup -->
    <div class="sx-modal" id="sxModal" hidden>
        <div class="sx-card" role="dialog" aria-modal="true">
            <button type="button" class="sx-close" id="sxClose" aria-label="Close">&times;</button>
            <img id="sxPhoto" class="sx-photo" alt="">
            <p class="sx-msg" id="sxMsg"></p>
        </div>
    </div>

    <!-- Letter popup -->
    <div class="sx-modal" id="sxLetterModal" hidden>
        <div class="sx-letter" role="dialog" aria-modal="true" aria-labelledby="sxLetterTitle">
            <button type="button" class="sx-close" id="sxLetterClose" aria-label="Close letter">&times;</button>
            <h3 class="sx-letter-title" id="sxLetterTitle"></h3>
            <div class="sx-letter-body" id="sxLetterBody"></div>
            <p class="sx-letter-from" id="sxLetterFrom"></p>
        </div>
    </div>

    <!-- "Are you sure you want to leave?" popup -->
    <div class="sx-modal" id="sxLeaveModal" hidden>
        <div class="sx-card sx-leave" role="dialog" aria-modal="true" aria-labelledby="sxLeaveTitle">
            <h3 class="sx-leave-title" id="sxLeaveTitle">Leave the surprise? 💗</h3>
            <p class="sx-leave-text">You can stay and look at the photos again. If you leave, you'll need to play the game again to see it.</p>
            <div class="sx-leave-actions">
                <button type="button" class="sx-letter-btn" id="sxStay">Stay 💗</button>
                <button type="button" class="sx-back" id="sxLeave">Leave</button>
            </div>
        </div>
    </div>
</div>