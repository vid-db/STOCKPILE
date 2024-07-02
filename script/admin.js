//------------SIDEBAR----------
let sidebarOpen = false;
const sidebar = document.getElementById('sidebar');
 
function openSideBar(){
  if(!sidebarOpen){
    sidebar.classList.add('sidebar-responsive');
    sidebarOpen = true;

  }
}
function closeSideBar(){
  if(sidebarOpen){
    sidebar.classList.remove('sidebar-responsive');
    sidebarOpen = false;
  }
}

// ------------LOGOUT-----------
let logout = document.getElementById('logout');
function droplog(){
  logout.classList.toggle("droplog");
}
// ---------- CHARTS ----------

// BAR CHART

const sales = document.getElementById('sales');
new Chart(sales, {
  type: 'bar',
  data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June','July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [{
      label: '2023-Monthly Sales',
      data: [124000, 144000, 309000, 400000, 288000, 388000,182000, 98900, 99000, 100000, 88800, 0],
      backgroundColor: [
        '#246dec', '#cc3c43', '#367952', '#f5b74f', '#4f35a1','#246dec', '#cc3c43', '#367952', '#f5b74f', '#4f35a1',
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 19, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
      ],
      borderWidth: 2
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// POLAR AREA CHART
const product = document.getElementById('product');

new Chart(product, {
  type: 'polarArea',
  data: {
    labels: ['RIMULLA R1', 'REVX HD40', 'DAIWA AIR FILTER D509', 'GEAR OIL 90', 'PRESTONE B.F'],
    datasets: [{
      label: 'Products',
      data: [24, 44, 82, 98, 88,],
      backgroundColor: [
        '#246dec', '#cc3c43', '#367952', '#f5b74f', '#4f35a1'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 19, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});





