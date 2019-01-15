import sys
import os
import pdf2md
from md2html import mdtohtml


def toMd(input,pdffile,flag):
	try:
		filename = pdffile
		title = os.path.splitext(os.path.basename(filename))[0]
		print('[*]Parsing : '+input + filename)
		parser = pdf2md.Parser(input + filename)
		parser.extract()
		piles = parser.parse()

		syntax = pdf2md.UrbanSyntax()

		writer = pdf2md.Writer()
		writer.set_syntax(syntax)
		writer.set_mode('simple')
		writer.set_title(title)
		writer.write(piles)

		print('[**]Your markdown is at : ' + writer.get_location())
		# flag = 1 :transfer to cs
		mdtohtml.mdtohtml(writer.get_location(),1)
		print('[**]Your html is at : ' + writer.get_location()+".html" )
		os.unlink(writer.get_location())
	except:
		print("[**]Error: except" )

def main(input,flag):
	ffs = "";
	for files in os.walk(input):
		ff = files[2]
	#print(ff)
	flist = []
	for f in ff:
		if "pdf" in f:
			toMd(input+"/",f,flag)
	#toMd("ss.pdf",1)

if __name__ == '__main__':
	main("input",1)
