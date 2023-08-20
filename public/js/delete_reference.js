const deleteRef = async (e) => {
    e.preventDefault();
    const link = e.currentTarget
    const url = link.getAttribute('href')

    const response = await fetch(url, {
        method: 'DELETE'
    })

    if (response.ok) {
        const item = link.closest('li')
        item.remove()
    }
}

document.querySelectorAll('.js-delete-ref').forEach((link) => {
    link.addEventListener('click', deleteRef)
})