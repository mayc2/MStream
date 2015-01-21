from django.shortcuts import render
from django.http import HttpResponse

# Create your views here.
def index(request):
    return HttpResponse("Account says hello! <br/> <a href='/accounts/about'>About</a>")

def about(request):
    return HttpResponse("This is the about page <br/> <a href='/accounts/'>Accounts</a>")