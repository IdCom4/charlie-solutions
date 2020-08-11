function updateOffset(param, newOffset, pageParam) {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set(param, newOffset);
    searchParams.set(pageParam, 0);
    window.location.search = searchParams.toString();
}