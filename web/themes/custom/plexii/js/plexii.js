/**
 * @file
 * plexii behaviors.
 */
(function (Drupal) {
  "use strict";

  Drupal.behaviors.plexii = {
    attach(context, settings) {
      console.log("It works!");
    },
  };

  /**
   *
   * Code for the social networking icons on the sidebar on article pages
   *
   */

  function populateSocialLinks(socialSite, container) {
    // Normalize jQuery → DOM element
    if (container && container.jquery) {
      container = container[0];
    }

    if (!(container instanceof HTMLElement)) {
      console.warn("populateSocialLinks: invalid container", container);
      return;
    }

    // Prevent duplicates
    if (container.querySelector(`.social-icon.${socialSite}`)) return;

    const i = document.createElement("i");

    const iconClass =
      socialSite === "twitter" ? "bi bi-twitter-x" : "bi bi-" + socialSite;
    i.className = iconClass;

    const a = document.createElement("a");
    // a.className = 'social-icon ${socialSite}';
    a.className = `social-icon ${socialSite}`;
    a.href = "#";

    a.addEventListener("click", function (e) {
      e.preventDefault();
      socialOpen(
        "https://" + socialSite + socialDict[socialSite] + window.location.href,
      );
    });

    a.appendChild(i);
    container.appendChild(a);
  }

  (function (Drupal, once) {
    Drupal.behaviors.socialShare = {
      attach(context) {
        once("social-share", "#sharebar", context).forEach((sharebar) => {
          socialLinks.forEach((item) => {
            populateSocialLinks(item, sharebar);
          });
        });
      },
    };
  })(Drupal, once);

  // raw share links https://crunchify.com/list-of-all-social-sharing-urls-for-handy-reference-social-media-sharing-buttons-without-javascript/
  // If you add a service you have to add the URL modifier to the dict
  /**
   * var socialDict = {
    facebook: ".com/sharer/sharer.php?u=",
    twitter: ".com/share?url=",
    pinterest: ".com/pin/create/bookmarklet/?url=",
    linkedin: ".com/shareArticle?url=",
    reddit: ".com/submit?url=",
  };
  
   */
  var socialDict = {
    facebook: ".com/sharer/sharer.php?u=",
    twitter: ".com/share?url=",
    linkedin: ".com/shareArticle?url=",
  };

  socialLinks = ["facebook", "twitter", "linkedin"];

  //   Run it
  // socialLinks.forEach((item) => populateSocialLinks(item, "sharebar"));

  // popup small window for share (plagiarised from somehwere.... not sure where)
  function socialOpen(url) {
    window.open(
      url,
      "links",
      "toolbar=0,location=1,directories=0,status=0,menubar=0,scrollbars=auto,resizable=yes,dependent=yes,width=400,height=400",
    );
    window.blur();
  }
  var links; //to avoid "undefined" message
  // JavaScript Unleashed's onUnload event handler
  function clean() {
    if (links != null) {
      links.close();
    }
  }
})(Drupal);
