<!-- resources/views/index.blade.php -->
@extends('layouts.app')

@section('title', 'Trang chủ')

{{-- @section('content')
    <div x-data="{ open: false }">
        <button @click="open = ! open">Toggle</button>

        <div x-show="open" @click.outside="open = false">Contents...</div>
    </div>
    <div x-data="{
        search: '',
    
        items: ['foo', 'bar', 'baz'],
    
        get filteredItems() {
            return this.items.filter(
                i => i.startsWith(this.search)
            )
        }
    }">
        <input x-model="search" placeholder="Search...">

        <ul>
            <template x-for="item in filteredItems" :key="item">
                <li x-text="item"></li>
            </template>
        </ul>
    </div>
@endsection --}}
