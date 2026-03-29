let jumlah = 1;

function tambah() {
  jumlah++;

  document.getElementById("jumlah").innerHTML = jumlah;
}

function kurang() {
  if (jumlah > 1) {
    jumlah--;
  }

  document.getElementById("jumlah").innerHTML = jumlah;
}

function hitung() {
  let harga = document.getElementById("harga").value;

  let total = harga * jumlah;

  document.getElementById("total").value = total;
}
