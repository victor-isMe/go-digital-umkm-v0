<div class="login-container">
    <div class="login-box">
        <form class="login-form" action="proses-registrasi.php" method="POST">

            <h3>Register Akun</h3>

            <div class="role-switch">
                <input id="penjual" type="radio" name="role" value="penjual" onclick="toggleForm()">
                <label for="penjual">Penjual</label>

                <input id="pembeli" type="radio" name="role" value="pembeli" checked onclick="toggleForm()">
                <label for="pembeli">Pembeli</label>
            </div>

            <br>

            <div id="formPembeli">
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" required><br>

                <label for="alamat">Alamat</label><br>
                <input type="text" name="alamat" required><br>

                <label for="nomor">No. Telp</label><br>
                <input type="tel" name="phone" required><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" required><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" required><br>
            </div>

            <div id="formPenjual" style="display: none;">
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" required><br>

                <label for="alamat">Alamat</label><br>
                <input type="text" name="alamat" required><br>

                <label for="nomor">No. Telp</label><br>
                <input type="tel" name="phone" required><br>

                <label for="toko">Nama Toko</label><br>
                <input type="text" name="toko" required><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" required><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" required><br>

                <div class="metode-pembayaran">
                    <label class="section-label">Metode Pembayaran</label>

                    <div class="opsi-metode-pembayaran">
                        <label>
                            <input type="checkbox" id="cbBank" name="bank" value="1" onchange="togglePaymentBlock('bank')"> Transfer Bank
                        </label>
                        <label>
                            <input type="checkbox" id="cbEwallet" name="ewallet" value="1" onchange="togglePaymentBlock('ewallet')">E-Wallet
                        </label>
                    </div>

                    <div id="blockBank" class="payment-block" style="display: none;">
                        <p class="block-title">Rekening Bank</p>
                        <div id="entriesBank"></div>
                        <button type="button" onclick="addPaymentEntry('bank')">+ Tambah Rekening</button>
                    </div>

                    <div id="blockEwallet" class="payment-block" style="display: none;">
                        <p class="block-title">E-Wallet</p>
                        <div id="entriesEwallet"></div>
                        <button type="button" onclick="addPaymentEntry('ewallet')">+ Tambah E-Wallet</button>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Daftar</button>
            <p style="font-size: 14px;">Sudah punya akun? <a href="index.php?page=login">Login</a></p>

        </form>
    </div>
</div>

<script>
    //Untuk atur swicth role (pembeli/penjual)
    document.addEventListener("DOMContentLoaded", toggleForm);

    function toggleForm() {
        const role = document.querySelector('input[name="role"]:checked').value;
        const formPenjual = document.getElementById('formPenjual');
        const formPembeli = document.getElementById('formPembeli');

        if(role === "penjual") {
            formPenjual.style.display = "block";
            formPembeli.style.display = "none";
            formPembeli.querySelectorAll('input').forEach(input => {
                input.disabled = true;
            });
            formPenjual.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });
        } else {
            formPenjual.style.display = "none";
            formPembeli.style.display = "block";
            formPenjual.querySelectorAll('input').forEach(input => {
                input.disabled = true;
            });
            formPembeli.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });
        }
    }

    //Untuk section metode pembayaran
    const providers = {
        bank: ['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'Danamon', 'BST', 'BSI'],
        ewallet: ['GoPay', 'OVO', 'Dana', 'ShopeePay']
    };

    const entryCount = {bank: 0, ewallet: 0};

    function togglePaymentBlock(type) {
        const block = document.getElementById('block' + capitalize(type));
        const isChecked = document.getElementById('cb' + capitalize(type)).checked;

        block.style.display = isChecked ? 'block' : 'none';

        if (isChecked) {
            if (document.getElementById('entries' + capitalize(type)).children.length === 0) {
                addPaymentEntry(type);
            }
        } else {
            document.getElementById('entries' + capitalize(type)).innerHTML = '';
            entryCount[type] = 0;
        }
    }

    function addPaymentEntry(type) {
        const index = ++entryCount[type];
        const container = document.getElementById('entries' + capitalize(type));

        const providerOptions = providers[type]
            .map(p => `<option value="${p}">${p}</option>`).join('');
        
        const row = document.createElement('div');
        row.className = 'payment-entry-row';
        row.id = `entry-${type}-${index}`;

        row.innerHTML = `
        <div class="entry-field">
            <label>${type === 'bank' ? 'Bank' : 'E-Wallet'}</label>
            <select name="payment[${type}][${index}][provider]" required>
                ${providerOptions}
            </select>
        </div>
        <div class="entry-field">
            <label>${type === 'bank' ? 'No. Rekening' : 'No. E-Wallet'}</label>
            <input type="text"
                    name="payment[${type}][${index}][nomor_akun]"
                    placeholder="${type === 'bank' ? 'xxx - xxx - xxx' : '085 - xxx - xxx'}"
                    required>
        </div>
        <div class="entry-field">
            <label>Nama Akun</label>
            <input type="text"
                    name="payment[${type}][${index}][nama_akun]"
                    placeholder="a.n Pemilik Akun"
                    required>
        </div>
        <button type="button" class="btn btn-danger" onclick="removePaymentEntry('${type}', ${index})">X</button>
        `;

        container.appendChild(row);
    }

    function removePaymentEntry(type, index) {
        const row = document.getElementById(`entry-${type}-${index}`);
        if (row) row.remove();
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>