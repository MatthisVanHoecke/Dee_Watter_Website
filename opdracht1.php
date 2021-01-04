<!DOCTYPE html>

<html>

<head>

<script type="text/javascript">

    function inloggen() {
        var ok = true;
        
        //invullen bevestiging
        
        //naam
        if(document.getElementById("naam").value == "") {
            document.getElementById("naamVerplicht").innerHTML = "Gelieve een naam in te vullen";
            ok = false;
        }
        else {
            document.getElementById("naamVerplicht").innerHTML = "";
            ok = true;
        }
        
        //paswoord
        if(document.getElementById("paswoord").value == "") {
            document.getElementById("paswoordVerplicht").innerHTML = "Gelieve een paswoord in te vullen";
            ok = false;
        }
        else {
            document.getElementById("paswoordVerplicht").innerHTML = "";
            ok = true;
        }
        
        
        //bevestig paswoord
        if(document.getElementById("paswoordConfirm").value == "") {
            document.getElementById("paswoordConfirmVerplicht").innerHTML = "Gelieve een bevestigingspaswoord in te vullen";
            ok = false;
        }
        else {
            document.getElementById("paswoordConfirmVerplicht").innerHTML = "";
            ok = true;
        }
        
        //foute gegevens
        if(document.getElementById("paswoord").value != "") {
            var string = document.getElementById("paswoord").value;
            var pattern = /(?=.*\d)(?=.*[A-Z]).{8,}/;
            if(pattern.test(string)) {
                document.getElementById("paswoordVerplicht").innerHTML = "";
                ok = true;
            }
            else {
                document.getElementById("paswoordVerplicht").innerHTML = "Ongeldig paswoord, bevat minstens 1 cijfer en 1 Hoofdletter en kleine letters, minimum 8 characters";
                ok = false;
            }
        }
        else {
            ok = false;
        }
        if(document.getElementById("paswoordConfirm").value != "") {
            var string = document.getElementById("paswoord").value;
            if(document.getElementById("paswoordConfirm").value != string) {
                document.getElementById("paswoordConfirmVerplicht").innerHTML = "Bevestigingspaswoord en paswoord komen niet overeen";
                ok = false;
            }
            else {
                ok = true;
            }
        }
        else {
            ok = false;
        }
        
        //form post
        if(ok == true) {
            document.inlogpagina.submit();
        }
    }

</script>

<style type="text/css">

.fout {

color: #F00;

}

</style>

</head>

<body>

<div class="hoofding">

<p>

inlogpagina:

</p>

</div>

<form name="inlogpagina" id="inlogpagina" method="post" action="aangemeld.php">

<table cellspacing="4">

<tr>

<td><label for="naam">Naam:</label></td>

<td><input type="text" name="naam" id="naam"></td>

<td><label id="naamVerplicht" class="fout"></label></td>

</tr>

<tr>

<tr>

<td><label for="paswoord">paswoord:</label></td>

<td><input type="password" name="paswoord" id="paswoord" ></td>

<td><label id="paswoordControle" class="fout"></label><label id="paswoordVerplicht" class="fout"></label></td>

</tr>

<tr>

<td><label for="paswoordConfirm">bevestiging paswoord:</label></td>

<td><input type="password" name="paswoordConfirm" id="paswoordConfirm" ></td>

<td><label id="paswoordConfirmControle" class="fout"></label><label id="paswoordConfirmVerplicht" class="fout"></label></td>

</tr>

<tr>

<td></td>

<td>

<input type="button" value="Inloggen" onClick="inloggen()"/>

</td>

</tr>

</table>

</form>

</body>

</html>