<h2>Išankstinė saskaita serija</h2>
<p>Pavadinimas: <input type="text" id="doc-name" name="doc-name"></p>
<p>Nr. <input type="text" id="doc-num" name="doc-num"></p>
<p>Paveikslėlis: <input type="file" id="doc-img" name="doc-img"></p>
<img id="image-preview" src="#">
<p>Klientas: <input type="text" id="customer" name="customer"></p>
<p>Siuntėjas: <input type="text" id="sender" name="sender"></p>
<table id="prod-table">
    <thead>
        <tr>
            <th>Prekės/Paslaugos Pavadinimas</th>
            <th>Kiekis</th>
            <th>Kaina</th>
            <th>Suma</th>
        </tr>
    </thead>
    <tbody>
        <tr id="row-1" row="1">
            <td><input type="text" id="prod-name-1" name="prod-name-1"></td>
            <td><input type="text" id="prod-ammo-1" name="prod-ammo-1" class="prod-amount"></td>
            <td><input type="text" id="prod-price-1" name="prod-price-1" class="prod-price"></td>
            <td>
                <p>
                    <span id="prod-total-1">0</span> Eur
                </p>
            </td>
            <td>
                <button class="del_row" row="1">Ištrinti eilę</button>
            </td>
        </tr>
    </tbody>
</table>
<button id="expand-prod-table">Pridėti prekę/paslaugą</button>
<p>Išrašymo data: <input type="date" id="date-of-issue" name="date-of-issue"></p>
<p>PVM (%) <input type="text" id="PVM" name="PVM"></p>
<p>PVM (Eur):
    <span id="PVM-eur">0</span>
</p>
<p>Viso su PVM (Eur):
    <span id="PVM-total">0</span>
</p>

<button id="save">Išsaugoti</button>
