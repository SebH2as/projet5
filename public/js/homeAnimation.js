const tl = gsap.timeline();


tl.fromTo("#navigationBar", 1,{opacity: 0, y: -100},{opacity: 1, y: 0})
tl.fromTo(".anim", 1,{opacity: 0},{opacity: 1})
tl.fromTo(".navBottomLink", 1,{opacity: 0},{opacity: 1, stagger: 0.25},"-=1")
tl.fromTo("#navLinks", 1,{width: "0%"},{width: "100%"},)
tl.fromTo("#title01", 1.5,{opacity: 0, left: "-1000"},{opacity: 1, left: "18%"},"-=1")
tl.fromTo("#title02", 1.5,{opacity: 0, right: "1000" },{opacity: 1, right: "20%"},"-=2")
tl.fromTo("header h3", 1.5,{opacity: 0},{opacity: 1},"-=.5")
tl.fromTo(".navTopLink", 1,{opacity: 0},{opacity: 1},"-=2.5")
tl.fromTo("#headerImg", 6,{opacity: 0, height: "0%"},{opacity: 1, height: "100%"},"-=6")
tl.fromTo("#anchorSum", 1,{opacity: 0},{opacity: 1},"-=2")
tl.fromTo("#anchorEd", 1,{opacity: 0},{opacity: 1},"-=2")

