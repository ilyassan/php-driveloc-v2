<!DOCTYPE html>
<html class="text-[12px] md:text-[14px] lg:text-[16px]">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href=<?= ASSETSROOT ."css/all.min.css"?> rel="stylesheet" />
    <link href=<?= ASSETSROOT ."css/fontawesome.min.css"?> rel="stylesheet" />
    <link href=<?= ASSETSROOT ."css/output.css"?> rel="stylesheet" />
  </head>
  <body>
    <nav class="container relative sm:py-4 flex justify-between">
      <div
        class="py-4 sm:p-0 bg-white relative flex justify-between flex-1 items-center z-20"
      >
        <a href="#" class="text-primary font-bold text-2xl">CAREX</a>
        <span
          id="burger-menu"
          class="sm:hidden cursor-pointer text-based text-3xl"
          ><i class="fa-solid fa-bars"></i>
        </span>
      </div>

      <ul id="menu" class="flex text-secondary bg-white shadow-md sm:shadow-none flex-col sm:flex-row absolute sm:static w-11/12 sm:w-fit left-1/2 -translate-x-1/2 sm:-translate-x-0 z-10 -top-[500%] py-4 sm:py-0 rounded-b-lg sm:rounded-none items-center sm:gap-10 text-based transition-all duration-500 font-semibold">
          <li
            class="w-full block text-center py-3 sm:p-0 sm:w-fit sm:pb-1 transition-all duration-300 <?= requestPath() == URLROOT ? 'text-primary border-primary' : 'sm:border-transparent'?> border-b sm:border-b-2 hover:text-primary hover:border-primary"
          >
            <a href="<?= URLROOT ?>">Home</a>
          </li>
          <li
            class="w-full block text-center py-3 sm:p-0 sm:w-fit sm:pb-1 transition-all duration-300 <?= requestPath() == URLROOT . "vehicles" || implode("/", array_slice(explode('/', requestPath()), 0, count(explode('/', requestPath())) - 1)) == URLROOT . "vehicles" ? 'text-primary border-primary' : 'sm:border-transparent'?> border-b sm:border-b-2 hover:text-primary hover:border-primary"
          >
            <a href="<?= URLROOT . "vehicles" ?>">Vehicles</a>
          </li>
          <?php
            if (isLoggedIn()) {
          ?>
              <li
                class="w-full block text-center py-3 sm:p-0 sm:w-fit sm:pb-1 transition-all duration-300 <?= requestPath() == URLROOT . "reservations" ? 'text-primary border-primary' : 'sm:border-transparent'?> border-b sm:border-b-2 hover:text-primary hover:border-primary"
              >
                <a href="<?= URLROOT . "reservations" ?>">Reservations</a>
              </li>
              <li
                class="w-full block text-center py-3 sm:p-0 sm:w-fit sm:pb-1 transition-all duration-300 <?= requestPath() == URLROOT . "themes" ? 'text-primary border-primary' : 'sm:border-transparent'?> border-b sm:border-b-2 hover:text-primary hover:border-primary"
              >
                <a href="<?= URLROOT . "themes" ?>">Blog</a>
              </li>
              <li
                class="cursor-pointer mt-5 sm:m-0 pt-1 pb-2 transition-all duration-300 bg-primary text-white px-2 rounded-lg"
              >
                <form action="<?= URLROOT . "logout" ?>" method="POST">
                  <button>Logout</button>
                </form>
              </li>
          <?php
            }else{
          ?>
              <li
                class="w-full block text-center py-3 sm:p-0 sm:w-fit sm:pb-1 transition-all duration-300 <?= requestPath() == URLROOT . "themes" ? 'text-primary border-primary' : 'sm:border-transparent'?> border-b sm:border-b-2 hover:text-primary hover:border-primary"
              >
                <a href="<?= URLROOT . "themes" ?>">Blog</a>
              </li>
              <li
                class="w-full block text-center py-3 sm:p-0 sm:w-fit sm:pb-1 transition-all duration-300 <?= requestPath() == URLROOT . "signup" ? 'text-primary border-primary' : 'sm:border-transparent'?> border-b sm:border-b-2 hover:text-primary hover:border-primary"
              >
                <a href="<?= URLROOT . "signup" ?>">Signup</a>
              </li>
          <?php } ?>
        </ul>
    </nav>