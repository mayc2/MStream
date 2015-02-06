from django.conf.urls import include, url, patterns
from django.contrib import admin
from accounts import views

urlpatterns = [
    # Examples:
    # url(r'^$', 'main_project.views.home', name='home'),
    # url(r'^blog/', include('blog.urls')),

    url(r'^admin/', include(admin.site.urls)),
    url(r'^accounts/', include('accounts.urls')),
]
