package kodluyoruz;

import java.util.Scanner;

public class NotOrtalamasi {

	public static void main(String[] args) {
		/*Java ile Matematik, Fizik, Kimya, Türkçe, Tarih, Müzik derslerinin sınav puanlarını kullanıcıdan
		alan ve ortalamalarını hesaplayıp ekrana bastırılan programı yazın.*/
		int Matematik,Fizik,Kimya,Türkçe,Tarih,Müzik;
		double ortalama;
		Scanner input = new Scanner(System.in) ;
		
		System.out.println("matematik notunuzu giriniz :");
		Matematik = input.nextInt();
		
		System.out.println("fizik notunuzu giriniz :");
		Fizik = input.nextInt();
		
		System.out.println("kimya notunuzu giriniz :");
		Kimya = input.nextInt();
		
		System.out.println("Türkçe notunuzu giriniz :");
		Türkçe = input.nextInt();
		
		System.out.println("Tarih notunuzu giriniz :");
		Tarih = input.nextInt();
		
		System.out.println("Müzik notunuzu giriniz :");
		Müzik = input.nextInt();
        
		ortalama = (Matematik + Fizik + Kimya + Türkçe + Tarih + Müzik)/6;
		
		String cevap = (ortalama > 60) ? "geçti" :"geçmedi" ;
		System.out.println(cevap);
	}

}
