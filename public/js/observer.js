const lefters = document.querySelectorAll(".lefters");
const righters = document.querySelectorAll(".righters");
const topers = document.querySelectorAll(".topers");
const downers = document.querySelectorAll(".downers");

const options = {
    root: null,
    threshold: 0,
    rootMargin: "250px"
};

const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
    entries.forEach((entry) => {
        if(!entry.isIntersecting) {
            return;
        }
        entry.target.classList.add('appear');
        appearOnScroll.unobserve(entry.target);
        
    });
}, options);

lefters.forEach(lefter => {
    appearOnScroll.observe(lefter);
});

righters.forEach(righter => {
    appearOnScroll.observe(righter);
});

topers.forEach(toper => {
    appearOnScroll.observe(toper);
});

downers.forEach(downer => {
    appearOnScroll.observe(downer);
});













