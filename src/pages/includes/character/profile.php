<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

/** @var Character $char */
if (isset($char) && !is_null($char)) {
    $newchar = false;
} else {
    $newchar = true;
}
?>

<fieldset id="profile">
    <div class="container">

        <div class="pb-3" id="charGroup1">
            <p><a data-toggle="collapse" href="#collapseCharacter" role="button" aria-expanded="false" aria-controls="collapseCharacter"><h3>Character</h3></a></p>
            <div class="collapse" id="collapseCharacter" data-parent="#charGroup1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="name">Name</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Name" aria-describedby="name">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="race">Race</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Race" aria-describedby="race">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0" id="career1">Career #1</span>
                    </div>
                    <input type="text" class="form-control rounded-0" aria-label="Career #1" aria-describedby="career1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary rounded-0" type="button">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-3" id="charGroup2">
            <p><a data-toggle="collapse" href="#collapsePersonalDetails" role="button" aria-expanded="false" aria-controls="collapsePersonalDetails"><h3>Personal Details</h3></a></p>
            <div class="collapse" id="collapsePersonalDetails" data-parent="#charGroup2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="age">Age</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Age" aria-describedby="age">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0 border-left-0" id="gender">Gender</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Gender" aria-describedby="gender">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="eyecolor">Eye Color</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Eye Color" aria-describedby="eyecolor">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0 border-left-0" id="weight">Weight</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Weight" aria-describedby="weight">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="haircolor">Hair Color</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Hair Color" aria-describedby="haircolor">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0 border-left-0" id="height">Height</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Height" aria-describedby="height">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="starsign">Star Sign</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Star Sign" aria-describedby="starsign">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0 border-left-0" id="numberofsiblings"># of Siblings</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="# of Siblings" aria-describedby="numberofsiblings">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-bottom-0 rounded-0" id="birthplace">Birthplace</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Birthplace" aria-describedby="birthplace">
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0" id="distinguishingmarks">Distinguishing Marks</span>
                    </div>
                    <input type="text" class="form-control rounded-0" aria-label="Distinguishing Marks" aria-describedby="distinguishingmarks">
                </div>
            </div>
        </div>

        <div class="pb-3" id="charGroup3">
            <p><a data-toggle="collapse" href="#collapseCharacterProfile" role="button" aria-expanded="false" aria-controls="collapseCharacterProfile"><h3>Character Profile</h3></a></p>
            <div class="collapse" id="collapseCharacterProfile" data-parent="#charGroup3">
                <div class="container">
                    <div class="row">
                        <div class="col-3">Main</div>
                        <div class="col">WS</div>
                        <div class="col">BS</div>
                        <div class="col">S</div>
                        <div class="col">T</div>
                        <div class="col">Ag</div>
                        <div class="col">Int</div>
                        <div class="col">WP</div>
                        <div class="col">Fel</div>
                    </div>
                </div>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text border-bottom-0 rounded-0 w-100" id="mainstarting">Starting</span>
                    </div>
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                    <input type="text" class="form-control form-control-sm border-bottom-0 rounded-0" aria-label="Starting" aria-describedby="mainstarting">
                </div>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text border-bottom-0 rounded-0 w-100" id="mainadvance">Advance</span>
                    </div>
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                    <input type="text" class="form-control border-bottom-0 rounded-0" aria-label="Advance" aria-describedby="mainadvance">
                </div>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend w-25">
                        <span class="input-group-text rounded-0 w-100" id="maincurrent">Current</span>
                    </div>
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                    <input type="text" class="form-control rounded-0" aria-label="Current" aria-describedby="maincurrent">
                </div>
            </div>
        </div>
    </div>
</fieldset>