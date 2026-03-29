// let cart = JSON.parse(localStorage.getItem("cart")) || [];
// updateCart();

let jumlah = 1;

//Untuk mangambil isi dari file header
// fetch("template/header.php")
//   .then(res => res.text())
//   .then(data => {
//     document.getElementById("header").innerHTML = data;
//   });
    
// //Untuk mengambil isi dari file footer
// fetch("template/footer.html")
//   .then(res => res.text())
//   .then(data => {
//     document.getElementById("footer").innerHTML = data;
//   });

//Fungsi untuk fitur menampilkan menu navigasi pada tampilan mobile
function toggleMenu() {
  const menu = document.getElementById("navMenu");
  menu.classList.toggle("active");
}

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
