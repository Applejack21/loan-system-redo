<template>
    <div class="flex flex-col space-y-5 lg:flex lg:flex-row items-center space-x-5 min-h-[44px] mb-7 -mt-5">
        <div class="flex-1">
            <slot name="title">
                <h2 class="text-2xl font-semibold">
                    <slot />
                </h2>
                <span class="text-sm text-gray-700">
                    <slot name="subTitle" />
                </span>
            </slot>
        </div>
        <slot name="button"></slot>
    </div>
</template>
