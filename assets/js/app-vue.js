
var app = new Vue({
    el: '#app',
    data: {
        message: { "test": 1 },
        pageTitles: { "home": "Home" },
        isSidebarHidden: false,
        isLoaderActive: false,
        windowWid: window.innerWidth,
        loadedWindowWid: window.innerWidth,
    },

    methods: {
        toggleSidebar: function(hide) {
            if(hide) {
                isSidebarHidden = true
            }
            const buttonClicked = document.getElementById("app-sidebar-toggle")
            setTimeout(() => buttonClicked.blur(), 50)

            if(!this.isSidebarHidden) {
                this.isSidebarHidden = true
            } else {
                this.isSidebarHidden = false
            }
        },
        checkWidth: function() {
            if(this.loadedWindowWid < 755) {
                this.isSidebarHidden =  true
            }
        },  
        toggleLoader: function() {
            if(!this.isLoaderActive) {
                this.isLoaderActive = true
            }
        }
    },
    
    beforeMount() {
        this.checkWidth();
    },

    mounted() {
        window.addEventListener("resize", () => {
            this.windowWid = window.innerWidth
        })
    },  
    
  });   
