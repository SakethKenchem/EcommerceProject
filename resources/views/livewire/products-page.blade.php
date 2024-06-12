<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
      <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
          <div class="flex flex-wrap mb-24 -mx-3">
              <div class="w-full pr-2 lg:w-1/4 lg:block">
                  <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                      <h2 class="text-2xl font-bold dark:text-gray-400"> Categories</h2>
                      <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                      <ul>
                        
                        @foreach ($categories as $category)
                        <li class="mb-4" wire:key="{{$category->id}}">
                          <label for="{{ $category->slug}}" class="flex items-center dark:text-gray-400 ">
                              <input type="checkbox" wire:model.live="selected_categories" id="{{$category->slug}}" value="{{$category->id}}" class="w-4 h-4 mr-2">
                              <span class="text-lg">{{$category->name}}</span>
                          </label>
                      </li>
                        @endforeach
                      </ul>

                  </div>
                  <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                      <h2 class="text-2xl font-bold dark:text-gray-400">Brand</h2>
                      <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                      <ul>
                        @foreach ($brands as $brand)
                          <li class="mb-4" wire:key="{{$brand->id}}">
                              <label for="{{$brand->slug}}" class="flex items-center dark:text-gray-300">
                                  <input type="checkbox" wire:model.live="selected_brands" id="{{$brand->slug}}" value="{{$brand->id}}" class="w-4 h-4 mr-2">
                                  <span class="text-lg dark:text-gray-400">{{$brand->name}}</span>
                              </label>
                          </li>
                        @endforeach
                      </ul>
                  </div>
                  <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                      <h2 class="text-2xl font-bold dark:text-gray-400">Product Status</h2>
                      <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                      <ul>
                          <li class="mb-4">
                              <label for="featured" class="flex items-center dark:text-gray-300">
                                  <input type="checkbox" id="featured" wire:model.live="featured" value="1" class="w-4 h-4 mr-2">
                                  <span class="text-lg dark:text-gray-400">Is Featured</span>
                              </label>
                          </li>
                          <li class="mb-4">
                              <label for="on_sale" class="flex items-center dark:text-gray-300">
                                  <input type="checkbox" id="on_sale" wire:model.live="on_sale" class="w-4 h-4 mr-2">
                                  <span class="text-lg dark:text-gray-400">On Sale</span>
                              </label>
                          </li>
                      </ul>
                  </div>

                  <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                      <h2 class="text-2xl font-bold dark:text-gray-400">Price</h2>
                      <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                      <div>
                          <label for="basic-range-slider-usage" class="sr-only">Example range</label>
                          <input type="range"
                              class="w-full bg-transparent cursor-pointer appearance-none disabled:opacity-50 disabled:pointer-events-none focus:outline-none
                [&::-webkit-slider-thumb]:w-2.5
                [&::-webkit-slider-thumb]:h-2.5
                [&::-webkit-slider-thumb]:-mt-0.5
                [&::-webkit-slider-thumb]:appearance-none
                [&::-webkit-slider-thumb]:bg-white
                [&::-webkit-slider-thumb]:shadow-[0_0_0_4px_rgba(37,99,235,1)]
                [&::-webkit-slider-thumb]:rounded-full
                [&::-webkit-slider-thumb]:transition-all
                [&::-webkit-slider-thumb]:duration-150
                [&::-webkit-slider-thumb]:ease-in-out
                [&::-webkit-slider-thumb]:dark:bg-neutral-700
              
                [&::-moz-range-thumb]:w-2.5
                [&::-moz-range-thumb]:h-2.5
                [&::-moz-range-thumb]:appearance-none
                [&::-moz-range-thumb]:bg-white
                [&::-moz-range-thumb]:border-4
                [&::-moz-range-thumb]:border-blue-600
                [&::-moz-range-thumb]:rounded-full
                [&::-moz-range-thumb]:transition-all
                [&::-moz-range-thumb]:duration-150
                [&::-moz-range-thumb]:ease-in-out
              
                [&::-webkit-slider-runnable-track]:w-full
                [&::-webkit-slider-runnable-track]:h-2
                [&::-webkit-slider-runnable-track]:bg-gray-100
                [&::-webkit-slider-runnable-track]:rounded-full
                [&::-webkit-slider-runnable-track]:dark:bg-neutral-700
              
                [&::-moz-range-track]:w-full
                [&::-moz-range-track]:h-2
                [&::-moz-range-track]:bg-gray-100
                [&::-moz-range-track]:rounded-full"
                              id="basic-range-slider-usage" max="20000" value="10" step="5">
                          <div class="flex justify-between ">
                              <span class="inline-block text-lg font-bold text-blue-400 ">$10</span>
                              <span class="inline-block text-lg font-bold text-blue-400 ">$ 20000</span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="w-full px-3 lg:w-3/4">
                  <div class="px-3 mb-4">
                      <div
                          class="items-center justify-between hidden px-3 py-2 bg-gray-100 md:flex dark:bg-gray-900 ">
                          <div class="flex items-center justify-between">
                              <select name="" id=""
                                  class="block w-40 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900">
                                  <option value="">Sort by latest</option>
                                  <option value="">Sort by Price</option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="flex flex-wrap items-center ">
                    @foreach ($products as $product)
                    <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3" wire:key="{{$product->id}}">
                      <div class="border border-gray-300 dark:border-gray-700">
                          <div class="relative bg-gray-200">
                              <a wire:navigate href="/products/{{ $product->slug }}" class="">
                                <img src="{{ url ('storage', $product->images[0]) }}"
                                  alt="{{$product->name}}" class="object-cover w-full h-56 mx-auto ">
                              </a>
                              </a>
                          </div>
                          <div class="p-3 ">
                              <div class="flex items-center justify-between gap-2 mb-2">
                                  <h3 class="text-xl font-medium dark:text-gray-400">
                                    {{$product->name}}
                                  </h3>
                              </div>
                              <p class="text-lg ">
                                  <span class="text-green-600 dark:text-green-600">${{$product->price}}</span>
                              </p>
                          </div>
                          <div class="flex justify-center p-4 border-t border-gray-300 dark:border-gray-700">

                              <a href="#"
                                  class="text-gray-500 flex items-center space-x-2 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-300">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                      fill="currentColor" class="w-4 h-4 bi bi-cart3 " viewBox="0 0 16 16">
                                      <path
                                          d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                      </path>
                                  </svg><span>Add to Cart</span>
                              </a>

                          </div>
                      </div>
                  </div>
                    @endforeach
                  </div>
                  <!-- pagination start -->
                  <div class="flex justify-end mt-6">
                      <!-- Pagination -->
                      {{$products->links()}}
                      <!-- End Pagination -->
                  </div>
                  <!-- pagination end -->
              </div>
          </div>
      </div>
  </section>

</div>