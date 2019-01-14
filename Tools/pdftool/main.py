import sys
import os
import pdf2md
from md2html import mdtohtml

	
def toMd(pdffile):
	filename = pdffile
	title = os.path.splitext(os.path.basename(filename))[0]
	print('Parsing '+filename)
	parser = pdf2md.Parser(filename)
	parser.extract()
	piles = parser.parse()

	syntax = pdf2md.UrbanSyntax()

	writer = pdf2md.Writer()
	writer.set_syntax(syntax)
	writer.set_mode('simple')
	writer.set_title(title)
	writer.write(piles)

	print('Your markdown is at' + writer.get_location())
	mdtohtml.mdtohtml(writer.get_location())

def main(argv):
	toMd("TT.pdf")

if __name__ == '__main__':
	main(sys.argv)