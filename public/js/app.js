function wobble(el)
{
    el.classList.add('wobble');
    setTimeout(_ => el.classList.remove('wobble'), 800);
}
