document.addEventListener("DOMContentLoaded", function () {
  const contactButton = document.getElementById("contact-button");
  const contactForm = document.getElementById("contact-form");
  // let search = document.getElementById('search-box');
  // let search_btn = document.getElementById('search-button');

  // contactButton.addEventListener('click', function () {
  //     contactForm.style.display = 'flex';
  // });

  // let searchForm = document.querySelector('.search-form');

  console.log("hello");
  const form = document.querySelector("form");
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    const name = formData.get("name");
    const email = formData.get("email");
    const message = formData.get("message");

    // For demonstration purposes, we'll just display a success message
    // In a real application, you would send this data to a server
    const successMessage = document.querySelector(".success-message");
    successMessage.textContent =
      "Thank you for getting in touch, " +
      name +
      "! We will get back to you soon.";

    form.reset();
  });
});

// const carouselElement = document.getElementById('carouselExampleIndicators');
// const carousel = new bootstrap.Carousel(carouselElement, {
//     interval: 3000,
//     pause: false
// });

// document.getElementById('carouselPause').addEventListener('click', function() {
//     carousel.pause();
//     this.classList.add('active');
//     document.getElementById('carouselPlay').classList.remove('active');
// });

// document.getElementById('carouselPlay').addEventListener('click', function() {
//     carousel.cycle();
//     this.classList.add('active'); 
//     document.getElementById('carouselPause').classList.remove('active');
// });


function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);


//     const swiper = new Swiper('.swiper-container', {
//         slidesPerView: 1,
//         spaceBetween: 10,
//         navigation: {
//             nextEl: '.swiper-button-next',
//             prevEl: '.swiper-button-prev',
//         },
//         pagination: {
//             el: '.swiper-pagination',
//             clickable: true,
//         },
//     });
// });

// // Carausel
// document.addEventListener('DOMContentLoaded', function() {
//     const carouselElement = document.getElementById('carouselExampleIndicators');
//     const carousel = new bootstrap.Carousel(carouselElement, {
//       interval: 2000,
//       pause: false
//     });

//     document.getElementById('carouselPlay').addEventListener('click', function() {
//       carousel.cycle();
//     });
// });

// Calendar
// const daysTag = document.querySelector(".days"),
// currentDate = document.querySelector(".current-date"),
// prevNextIcon = document.querySelectorAll(".icons span");

// // getting new date, current year and month
// let date = new Date(),
// currYear = date.getFullYear(),
// currMonth = date.getMonth();

// // storing full name of all months in array
// const months = ["January", "February", "March", "April", "May", "June", "July",
//               "August", "September", "October", "November", "December"];

// const renderCalendar = () => {
//     let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
//     lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
//     lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // getting last day of month
//     lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
//     let liTag = "";

//     for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
//         liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
//     }

//     for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month
//         // adding active class to li if the current day, month, and year matched
//         let isToday = i === date.getDate() && currMonth === new Date().getMonth()
//                      && currYear === new Date().getFullYear() ? "active" : "";
//         liTag += `<li class="${isToday}">${i}</li>`;
//     }

//     for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
//         liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
//     }
//     currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
//     daysTag.innerHTML = liTag;
// }
// renderCalendar();

// prevNextIcon.forEach(icon => { // getting prev and next icons
//     icon.addEventListener("click", () => { // adding click event on both icons
//         // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
//         currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

//         if(currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
//             // creating a new date of current year & month and pass it as date value
//             date = new Date(currYear, currMonth, new Date().getDate());
//             currYear = date.getFullYear(); // updating current year with new date year
//             currMonth = date.getMonth(); // updating current month with new date month
//         } else {
//             date = new Date(); // pass the current date as date value
//         }
//         renderCalendar(); // calling renderCalendar function
//     });
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const carouselElement = document.getElementById('carouselExampleIndicators');
//     const carousel = new bootstrap.Carousel(carouselElement, {
//         interval: 3000,
//         pause: false
//     });
// });
